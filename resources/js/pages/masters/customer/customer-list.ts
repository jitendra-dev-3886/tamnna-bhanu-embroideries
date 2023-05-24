/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";

import { HTMLClassModule } from "../../../store/htmlclass";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { CategoryModule } from "../../../store/category";

import { ITableHeaders } from "../../../../assets/types/table";

import { ICustomerModel } from "../../../../assets/types/customer";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";

import {
    IConfirmationProps,
    IDeleteProps,
    IExportProps,
    IImportProps,
    IParamProps,
    ResponseResult,
} from "../../../../assets/types/common";
import { CustomerModule } from "@/store/customer";
import { SnackbarModule } from "@/store/snackbar";

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        Import,
    },
})
class Customer extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "customers";
    baseUrl = process.env.MIX_API_BASE_URL;

    headers: ITableHeaders[] = [
        { text: "Name", value: "name" },
        { text: "Contact Number", value: "contact_number" },
        { text: "Status", value: "user_status" },
        { text: "Actions", value: "actions", sortable: false },
    ];

    customSortableKeys = {};
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
        store: "customer", //as per Module Name
        modelName: "customer", //as per Module Name
        multiple: false,
    };

    exportProps: IExportProps = {
        ids: "",
        fileName: "Customer",
        apiName: "customers-export",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };

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
     * Delete Customer
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "customer";
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
        this.deleteProps.store = "customer";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }
    onEdit(id: string): void {
        CustomerModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/customer/edit/" + id);
    }
    /**
     * View Category
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CustomerModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<ICustomerModel>>) => {
                CustomerModule.SET_MODEL(response.data.data as ICustomerModel);
                HTMLClassModule.removeBodyClassName("page-loading");
                //this.categoryViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    changeStatus(id: string, status: string): void {
        HTMLClassModule.addBodyClassName("page-loading");

        if (status == "1") {
            status = "0"; // request deactivation when user status is active
        } else if (status == "0") {
            status = "1"; // request activation when user status is inactive
        }

        CustomerModule.setCustomerStatus({
            user_id: id,
            user_status: status,
        }).then(
            (response: AxiosResponse<ResponseResult<ICustomerModel>>) => {
                this.refresh();
                SnackbarModule.setMsg(response.data.message as string);

                HTMLClassModule.removeBodyClassName("page-loading");
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
}
export default Customer;
