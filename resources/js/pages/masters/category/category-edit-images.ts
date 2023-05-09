
import { Prop } from "vue-property-decorator";
import { CategoryModule } from "@/store/category";
import CommonApis from "@/mixins/common-apis";
import { ICategoryFullResponse } from "../../../../assets/types/category";
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
@Component({
    components: {
        DeleteModal
    },
})
class CategoryEditImages extends mixins(ServerTable, CommonApis) {
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

    get model(): ICategoryFullResponse {
        return CategoryModule.viewModel;
    }

    onCancel(): void {
        this.onModalClear("category", "CLEAR_STORE");
        this["$router"].push("/masters/category");
    }
    deleteItem(id: string | number): void {
        debugger;
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "category";
        this.confirmation.title = this.$getConst("DELETE_TITLE");
        this.confirmation.description = this.$getConst("WARNING");
        this.modalOpen = true;
    }


}
export default CategoryEditImages;
