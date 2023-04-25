import CommonServices from "@/mixins/common";
import { Prop } from "vue-property-decorator";
import { HomeBannerModule } from "@/store/homebanner";
import CommonApis from "@/mixins/common-apis";
import { IHomeBannerFullResponse } from "../../../../assets/types/homebanner";
import Component, { mixins } from "vue-class-component";
import DeleteModal from "../../../components/DeleteModal.vue";
import {
    IExportProps,
    IParamProps,
    IConfirmationProps,
    IDeleteProps,
    IImportProps,
    ResponseResult,
} from "../../../../assets/types/common";
import ServerTable from "@/mixins/customtable/server-table";
@Component
class HomeBannerEditImages extends mixins(ServerTable, CommonApis) {
    modalOpen=false;
    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this.$getConst("BTN_CANCEL"),
        btnConfirmationText: this.$getConst("BTN_OK"),
    };
    deleteProps: IDeleteProps = {
        ids: "",
        store: "",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    @Prop({ default: false }) value!: boolean;

    get model(): IHomeBannerFullResponse {
        return HomeBannerModule.viewModel;
    }

    onCancel(): void {
        this.onModalClear("homebanner", "CLEAR_STORE");
        this["$router"].push("/masters/homebanner");
    }
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "user";
        this.confirmation.title = this.$getConst("DELETE_TITLE");
        this.confirmation.description = this.$getConst("WARNING");
        this.modalOpen = true;
    }


}
export default HomeBannerEditImages;
