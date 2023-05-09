import CommonServices from "@/mixins/common";
import { Prop } from "vue-property-decorator";
import { HomeBannerModule } from "@/store/homebanner";
import { IHomeBannerFullResponse } from "../../../../assets/types/homebanner";
import Component, { mixins } from "vue-class-component";
import DeleteConfirm from "../../../components/DeleteConfirm.vue";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { IParamProps, ResponseResult } from "../../../../assets/types/common";
import { SnackbarModule } from "@/store/snackbar";
import { AxiosResponse } from "axios";
@Component({
    components: {
        DeleteConfirm,
        ErrorBlockServer,
    },
})
class HomeBannerEditImages extends mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    deleteConfirm = false;
    addImagesLoading = false;
    errorMessage = "";
    featured_image: string | Blob = "";

    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };

    get model(): IHomeBannerFullResponse {
        return HomeBannerModule.viewModel;
    }

    uploadFeatureImg(): void {
        const formData = new FormData();
        let apiName = "updateFeatureImg";
        this.addImagesLoading = true;
        formData.append("featured_image", this.featured_image);

        HomeBannerModule[apiName]({
            images: formData,
            editId: this.model.id,
        }).then(
            (
                response: AxiosResponse<ResponseResult<IHomeBannerFullResponse>>
            ) => {
                if (response.data.data) {
                    this.addImagesLoading = false;
                    this.featured_image = "";
                    // TODO: need to update the existing image
                    HomeBannerModule.SET_VIEW_MODEL(
                        response.data.data as IHomeBannerFullResponse
                    );
                }

                // Success message
                SnackbarModule.setMsg(response.data.message as string);
            },
            (error) => {
                this.addImagesLoading = false;
                this.errorMessage = this.getAPIErrorMessage(error.response);
            }
        );
    }

    deleteFeatureImg(payload: IParamProps): void {
        this.deleteConfirm = false;
        let apiName = "deleteFeatureImg";

        HomeBannerModule[apiName](payload.idProps as string).then(
            (response: AxiosResponse<ResponseResult<boolean>>) => {
                this.model.featured_image = "";
                SnackbarModule.setMsg(response.data.message as string);
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    onCancel(): void {
        this.onModalClear("homebanner", "CLEAR_STORE");
        this["$router"].push("/masters/homebanner");
    }

    /* Delete Modal  */
    confirmDelete(id: string | number, index: number | string): void {
        this.paramProps.idProps = id;
        this.paramProps.indexProps = index; // when index will be "" that means its for feature image delete confirmation
        this.deleteConfirm = true;
    }
}
export default HomeBannerEditImages;
