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
    ICategoryUpdatePayload,
} from "../../../../assets/types/category";

import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";

import { ResponseResult } from "../../../../assets/types/common";
import { HTMLClassModule } from "@/store/htmlclass";

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
                key: "required",
                value: "Name required",
            },
            {
                key: "max",
                value: "Maximum length should be 191",
            },
        ],

        description: [
            {
                key: "required",
                value: "Remarks required",
            },
        ],

        featured_image: [
            {
                key: "required",
                value: "Feature Image required",
            },
            {
                key: "size",
                value: "Maximum size allowed is 1 MB",
            },
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
        ],
    };

    isSubmitting = false;
    description = "";
    get model(): ICategoryModel {
        return CategoryModule.model;
    }

    get isEditMode(): boolean {
        return CategoryModule.editId
            ? <number>CategoryModule.editId > 0
            : false;
    }

    get parentCategoryList(): ICategoryFullResponse[] {
        return CategoryModule.parentCategoryList;
    }

    //Methods

    convertDomDesc() {
        let descStr: string = this.model.description;
        var parser = new DOMParser();
        var doc = parser.parseFromString(descStr, "text/html");
        this.description = doc.body.innerText.toString();
    }

    clearDescrptn() {
        this.convertDomDesc();
        if (this.description == "") {
            this.model.description = "";
        }
    }

    /* JSON Form Submit - Start*/
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                // For Edit Category
                if (
                    CategoryModule.editId &&
                    <number>CategoryModule.editId > 0
                ) {
                    apiName = "edit";
                    editId = CategoryModule.editId;
                }

                //To Submit JSON Request
                //const sendParamModel = JSON.parse(JSON.stringify(self.model));

                const formData = new FormData();
                const payload: ICategoryUpdatePayload = {
                    name: "",
                    description: "",
                    parent_id: "",
                };

                if (!this.isEditMode) {
                    formData.append("name", this.model.name);
                    formData.append("description", this.model.description);
                    formData.append(
                        "featured_image",
                        this.model.featured_image
                    );
                    formData.append(
                        "parent_id",
                        this.model.parent_id ? this.model.parent_id : ""
                    );
                } else {
                    payload.name = this.model.name;
                    payload.description = this.model.description;

                    payload.parent_id = this.model.parent_id
                        ? this.model.parent_id
                        : "";
                }

                CategoryModule[apiName]({
                    model: !this.isEditMode ? formData : payload,
                    editId,
                }).then(
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

    getParentCategoryList(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CategoryModule.getParentCategoryList().then(
            (
                response: AxiosResponse<ResponseResult<ICategoryFullResponse[]>>
            ) => {
                CategoryModule.SET_PARENT_CATEGORY_LIST(
                    response.data.data as ICategoryFullResponse[]
                );
                HTMLClassModule.removeBodyClassName("page-loading");
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    created(): void {
        this.getParentCategoryList();
        this.resetStoreData("category").then(
            (response: unknown) => {
                const castedCategoryResponse = response as AxiosResponse<
                    ResponseResult<ICategoryFullResponse>
                >;
                if (castedCategoryResponse?.data?.data) {
                    const categoryModel: ICategoryModel = {
                        name: castedCategoryResponse.data?.data?.name,
                        description:
                            castedCategoryResponse.data?.data?.description,
                        featured_image:
                            castedCategoryResponse.data?.data?.featured_image,
                        parent_id: castedCategoryResponse.data?.data?.parent_id,
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
