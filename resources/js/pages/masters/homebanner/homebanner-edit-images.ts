import CommonServices from "@/mixins/common";
import { Prop } from "vue-property-decorator";
import { HomeBannerModule } from "@/store/homebanner";
import { IHomeBannerFullResponse } from "../../../../assets/types/homebanner";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { IParamProps, ResponseResult } from "../../../../assets/types/common";
import { SnackbarModule } from "@/store/snackbar";
import { AxiosResponse } from "axios";
@Component({
    components: {
        ErrorBlockServer,
    },
})
class HomeBannerEditImages extends mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
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
                    (this.$parent as any)["getData"]();
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

    onCancel(): void {
        this.onModalClear("homebanner", "CLEAR_STORE");
        this["$router"].push("/masters/homebanner");
    }
}
export default HomeBannerEditImages;
