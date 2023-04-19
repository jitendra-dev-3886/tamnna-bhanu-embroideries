import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../../mixins/common";
import CommonApis from "../../../mixins/common-apis";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { CategoryModule } from "../../../store/category";
import { SnackbarModule } from "../../../store/snackbar";

import {
    ICategoryModel,
    ICategoryFullResponse,
    ICategoryValidations,
} from "../../../../assets/types/category";


import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";


import {
    ResponseResult,
} from "../../../../assets/types/common";



@Component({
    components: {
        ErrorBlockServer,

    },
})
class AddEditCategory extends Mixins(CommonServices, CommonApis) {
    errorDialog = false;

    errorMessage = "";

    validationMessages: ICategoryValidations = {

                name: [
                    {
                        key: 'required',
                        value: 'Name required'
                    },
                    {
                        key: 'max',
                        value: 'Maximum length should be 191'
                    },
                ],

                description: [
                    {
                        key: 'required',
                        value: 'Description required'
                    },
                ],

                featured_image: [
                    {
                        key: 'required',
                        value: 'Featured Image required'
                    },
                    {
                        key: 'max',
                        value: 'Maximum length should be 500'
                    },
                ],
    };

    isSubmitting = false;



    get model(): ICategoryModel {
        return CategoryModule.model;
    }

    get isEditMode(): boolean {
        return CategoryModule.editId ? CategoryModule.editId > 0 : false;
    }



    //Methods

         /* JSON Form Submit - Start*/
    onSubmit(): void{

        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {

                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                // For Edit Category
                if (CategoryModule.editId && CategoryModule.editId > 0) {
                    apiName = "edit";
                    editId = CategoryModule.editId;
                }

                //To Submit JSON Request
               //const sendParamModel = JSON.parse(JSON.stringify(self.model));

                const formData = new FormData();
                formData.append("name", this.model.name);
                formData.append("description", this.model.description);
                formData.append("featured_image", this.model.featured_image);




                CategoryModule[apiName]({ model: formData, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse>
                        >
                    ) => {
                        this.onCancel();
                        SnackbarModule.setMsg(response.data.message as string);
                    },
                    (error) => {
                        self.isSubmitting = false;
                        self.errorMessage = self.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }
    /* JSON Form Submit - End*/

    /* Cancel */
    onCancel(): void {
        this.onModalClear("category", "CLEAR_STORE");
        this["$router"].push("/masters/category");
    }



    created(): void {

        this.resetStoreData("category").then(
            (response: unknown) => {
                const castedCategoryResponse = response as AxiosResponse<
                    ResponseResult<ICategoryFullResponse>
                >;
                if (castedCategoryResponse?.data?.data) {



                    const categoryModel: ICategoryModel = {
                        name: castedCategoryResponse.data?.data?.name,
                        description: castedCategoryResponse.data?.data?.description,
                        featured_image: castedCategoryResponse.data?.data?.featured_image,
                    };

                    CategoryModule.SET_MODEL(categoryModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );


    }
}

export default AddEditCategory;