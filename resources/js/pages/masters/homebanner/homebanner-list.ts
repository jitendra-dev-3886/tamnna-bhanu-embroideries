/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";
//import homeBannerViewModal from "./homeBannerViewModal.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { HomeBannerModule } from "../../../store/homebanner";

import {
    ITableHeaders,
} from "../../../../assets/types/table";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";
import { IHomeBannerFullResponse } from '../../../../assets/types/homebanner';
import HomeBannerViewModal from './HomeBannerViewModal.vue';
import {
    IConfirmationProps,
    IDeleteProps,
    IExportProps,
    IImportProps,
    IParamProps,
    ResponseResult,
} from "../../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        HomeBannerViewModal,
        Import,
    },
})
class HomeBanner extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "homebanners";

    headers: ITableHeaders[] = [
        { text: 'Name', value: 'name' },
       // { text: 'Status', value: 'status' },
        { text: 'Featured Image', value: 'featured_image' },
        { text: 'Actions', value: 'actions', sortable: false },
            ];

    customSortableKeys = {
            };
    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this["$getConst"]("BTN_CANCEL"),
        btnConfirmationText: this["$getConst"]("BTN_OK"),
    };
    deleteProps: IDeleteProps = {
        ids: "",
        store: "",
    };
    importProps: IImportProps = {
        store: "homebanner", //as per Module Name
        modelName: "homebanner", //as per Module Name
        multiple: false,
    };

    exportProps: IExportProps = {
        ids: "",
        fileName: "HomeBanner",
        apiName: "homebanners-export",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };


    homeBannerViewModal = false;

    /**
     * Redirect to add homebanner page
     * */
    addHomebanner(): void {
        this["$router"].push("/masters/homebanner/add");
    }

    /**
     * Export data in CSV file
     */
    setExport(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element, index) => {
            rowIds[index] = element.id;
        });

        this.exportProps.ids = rowIds;
        (<any>this.$refs.exportbtn).exportToCSV();
    }

    /**
     * Delete homebanner
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "homebanner";
        this.confirmation.title = this["$getConst"]("DELETE_TITLE");
        this.confirmation.description = this["$getConst"]("WARNING");
        this.modalOpen = true;
    }

    /**
     * Multiple Delete
     */
    multipleDelete(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element) => {
            rowIds.push(element.id);
        });

        this.deleteProps.ids = rowIds;
        this.deleteProps.store = "homebanner";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }

    onEdit(id: string | number): void {
        HomeBannerModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/homebanner/edit/" + id);
    }
    /**
     * View homebanner
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        HomeBannerModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IHomeBannerFullResponse>>) => {
                HomeBannerModule.SET_VIEW_MODEL(
                    response.data.data as IHomeBannerFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.homeBannerViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    refreshData(): void {
        const self = this;
        setTimeout(function () {
            if (self.tab == "tab1") {
                self.refresh();
            } else if (self.tab == "tab2" && self.$refs.importdata) {
                if (self.isImportLoaded) {
                     (self.$refs.importdata as any).refreshImport();
                }
                self.isImportLoaded = true;
            }
        }, 100);
    }




    created(): void {

    }
}
export default HomeBanner;
