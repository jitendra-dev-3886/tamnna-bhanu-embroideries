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
    IHomeBannerUpdatePayload,
} from "../../../../assets/types/homebanner";

import { AxiosResponse } from "axios";

import { ResponseResult } from "../../../../assets/types/common";

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
                key: "required",
                value: "Name required",
            },
            {
                key: "max",
                value: "Maximum length should be 191",
            },
        ],

        banner_status: [
            {
                key: "required",
                value: "Banner status required",
            },
        ],

        featured_image: [
            {
                key: "required",
                value: "Featured Image required",
            },
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "size",
                value: "Maximum size allowed is 1 MB",
            },
        ],
    };

    isSubmitting = false;

    get model(): IHomeBannerModel {
        return HomeBannerModule.model;
    }

    get isEditMode(): boolean {
        return HomeBannerModule.editId
            ? <number>HomeBannerModule.editId > 0
            : false;
    }

    //Methods

    /* JSON Form Submit - Start*/
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                this.isSubmitting = true;
                this.errorMessage = "";
                let apiName = this.isEditMode ? "edit" : "create";
                let editId: string | number = "";
                const formData = new FormData();
                const payload: IHomeBannerUpdatePayload = {
                    name: "",
                    banner_status: "",
                };

                if (this.isEditMode) {
                    editId = <number>HomeBannerModule.editId;

                    payload.name = this.model.name;
                    payload.banner_status = this.model.banner_status;
                } else {
                    formData.append("name", this.model.name);
                    formData.append("banner_status", this.model.banner_status);
                    formData.append(
                        "featured_image",
                        this.model.featured_image
                    );
                }

                HomeBannerModule[apiName]({
                    model: this.isEditMode ? payload : formData,
                    editId,
                }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IHomeBannerFullResponse>
                        >
                    ) => {
                        this.errorMessage = "";
                        this.onCancel();
                        SnackbarModule.setMsg(response.data.message as string);
                    },
                    (error) => {
                        this.isSubmitting = false;
                        this.errorMessage = this.getAPIErrorMessage(
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
                        banner_status:
                            castedCategoryResponse.data?.data?.banner_status,
                        featured_image:
                            castedCategoryResponse.data?.data?.featured_image,
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
