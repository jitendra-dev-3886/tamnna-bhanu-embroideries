import { Prop } from "vue-property-decorator";
import { CategoryModule } from "@/store/category";
import CommonApis from "@/mixins/common-apis";
import { ICategoryFullResponse } from "../../../../assets/types/category";
import Component, { mixins } from "vue-class-component";

import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { IParamProps, ResponseResult } from "../../../../assets/types/common";
import ServerTable from "@/mixins/customtable/server-table";
import { AxiosResponse } from "axios";
import { SnackbarModule } from "@/store/snackbar";
@Component({
    components: {
        ErrorBlockServer,
    },
})
class CategoryEditImages extends mixins(ServerTable, CommonApis) {
    @Prop({ default: false }) value!: boolean;
    addImagesLoading = false;
    errorMessage = "";
    featured_image: string | Blob = "";

    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };

    get model(): ICategoryFullResponse {
        return CategoryModule.viewModel;
    }

    onCancel(): void {
        this.onModalClear("category", "CLEAR_STORE");
        this["$router"].push("/masters/category");
    }

    uploadFeatureImg(): void {
        const formData = new FormData();
        let apiName = "updateFeatureImg";
        this.addImagesLoading = true;
        formData.append("featured_image", this.featured_image);

        CategoryModule[apiName]({
            images: formData,
            editId: this.model.id,
        }).then(
            (
                response: AxiosResponse<ResponseResult<ICategoryFullResponse>>
            ) => {
                if (response.data.data) {
                    this.addImagesLoading = false;
                    this.featured_image = "";
                    // TODO: need to update the existing image
                    CategoryModule.SET_VIEW_MODEL(
                        response.data.data as ICategoryFullResponse
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
}

export default CategoryEditImages;
