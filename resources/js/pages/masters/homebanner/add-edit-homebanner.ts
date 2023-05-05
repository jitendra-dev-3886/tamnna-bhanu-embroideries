import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../../mixins/common";
import CommonApis from "../../../mixins/common-apis";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { HomeBannerModule } from "../../../store/homebanner";
import { SnackbarModule } from "../../../store/snackbar";

import {
    IHomeBannerModel,
    IHomeBannerFullResponse,
    IHomeBannerValidations,
} from "../../../../assets/types/homebanner";


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
class AddEditHomeBanner extends Mixins(CommonServices, CommonApis) {
    errorDialog = false;

    errorMessage = "";

    validationMessages: IHomeBannerValidations = {

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

                banner_status: [
                    {
                        key: 'required',
                        value: 'Banner status required'
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



    get model(): IHomeBannerModel {
        return HomeBannerModule.model;
    }

    get isEditMode(): boolean {
        return HomeBannerModule.editId ? HomeBannerModule.editId > 0 : false;
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

                // For Edit HomeBanner
              /*  if (HomeBannerModule.editId && HomeBannerModule.editId > 0) {
                    apiName = "edit";
                    editId = HomeBannerModule.editId;
                    const sendParamModel = JSON.parse(JSON.stringify(self.model));
                    HomeBannerModule[apiName]({ model: sendParamModel, editId }).then(
                        (
                            response: AxiosResponse<
                                ResponseResult<IHomeBannerFullResponse>
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

                //To Submit JSON Request
               //const sendParamModel = JSON.parse(JSON.stringify(self.model));*/


                const formData = new FormData();
                formData.append("name", this.model.name);
                formData.append("banner_status", this.model.banner_status);
                formData.append("featured_image", this.model.featured_image);


                HomeBannerModule[apiName]({ model: formData, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IHomeBannerFullResponse>
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

    EditDetail():void{
        const self = this;

        let editId: string | number = "";
        let apiName="homebanners";
                // For Edit HomeBanner
                if (HomeBannerModule.editId && HomeBannerModule.editId > 0) {
                    apiName = "edit";
                    editId = HomeBannerModule.editId;

                    const sendParamModel = JSON.parse(JSON.stringify(self.model));

                    HomeBannerModule[apiName]({ model: sendParamModel, editId }).then(
                        (
                            response: AxiosResponse<
                                ResponseResult<IHomeBannerFullResponse>
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

    }
    /* Cancel */
    onCancel(): void {
        this.onModalClear("homebanner", "CLEAR_STORE");
        this["$router"].push("/masters/homebanner");
    }
    created(): void {

        this.resetStoreData("homebanner").then(
            (response: unknown) => {
                const castedCategoryResponse = response as AxiosResponse<
                    ResponseResult<IHomeBannerFullResponse>
                >;
                if (castedCategoryResponse?.data?.data) {
                    const homeBannerModel: IHomeBannerModel = {
                        name: castedCategoryResponse.data?.data?.name,
                        banner_status: castedCategoryResponse.data?.data?.banner_status,
                        featured_image: castedCategoryResponse.data?.data?.featured_image,
                    };

                    HomeBannerModule.SET_MODEL(homeBannerModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );


    }
}

export default AddEditHomeBanner;
