/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import MultiFileModal from "@/components/MultiFileModal.vue";
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";
import OrderViewModal from "./OrderViewModal.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import ImageViewer from "@/components/ImageViewer.vue";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { CommonModule } from "@/store/common";
import { UserModule } from "@/store/user";
import { IUserLightResponse } from "../../../../assets/types/user";
import { OrderModule } from "../../../store/order";

import {
    ITableHeaders,
    IFilterModel,
} from "../../../../assets/types/table";

import {
    IOrderProducts,
    IOrderFullResponse,
} from "../../../../assets/types/order";

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

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        OrderViewModal,
        Import,
        MultiFileModal,
        ImageViewer,
    },
})
class Order extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "orders";

    headers: ITableHeaders[] = [
        { text: 'Customer', value: 'user_id' },
       // { text: 'Quantity', value: 'quantity' },
       {text:'Products',value:'order_products'},
        { text: 'Gst', value: 'gst' },
        { text: 'Payment Amount', value: 'payment_amount' },
      // { text: 'Order Status', value: 'order_status_text' },
        { text: 'Actions', value: 'actions', sortable: false },
            ];

    customSortableKeys = {order_status_text: "order_status",
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
        store: "order", //as per Module Name
        modelName: "order", //as per Module Name
        multiple: false,
    };

    importZipProps: IImportProps = {
        store: "order",
        modelName: "order",
        zipName: "zipUpload",
        multiple: false,
    };
    exportProps: IExportProps = {
        ids: "",
        fileName: "Order",
        apiName: "orders-export",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    user_id="";
    filterMenu= false;
    filterModel: IFilterModel = {};


    images: { thumbnail: string; source: string }[] = [];
    orderViewModal = false;


    get userList(): IUserLightResponse[] {
        return UserModule.userList;
    }

    get productIdArr(): IOrderProducts[] {
        return OrderModule.product_idList;
    }

    /**
     * Redirect to add order page
     * */
    addOrder(): void {
        this["$router"].push("/masters/order/add");
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
     * Delete Order
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "order";
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
        this.deleteProps.store = "order";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }

    onEdit(id: string | number): void {
        OrderModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/order/edit/" + id);
    }
    /**
     * View Order
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        OrderModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IOrderFullResponse>>) => {
                OrderModule.SET_VIEW_MODEL(
                    response.data.data as IOrderFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.orderViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }


    /**
     * Change Filter
     */
    changeFilter() {
       const filter: IFilterModel = {};

       if (this.user_id != '') {
         filter.user_id = [this.user_id];
       }

       this.filterModel = filter;
       this.refresh();
       this.filterMenu = false;
     }

    /**
     * Reset Filter
     */
    resetFilter(): void {
      this.user_id = "";
        this.changeFilter();
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


    /*
    * Open Product Id
    * @param row
    * */
    openProductId(row: IOrderFullResponse): void {
        this.images = [];
        const index = (this.tableData as IOrderFullResponse[]).findIndex((item) => item.id == row.id);
        if (index != undefined && index >= 0) {
            for (let i = 0; i < row.order_products.length; i++) {
                const obj = {
                    thumbnail: row.order_products[i].product_id_thumbnail,
                    source: row.order_products[i].product_id,
                };
                this.images.push(obj);
                this.$forceUpdate();
            }
            const self = this;
            setTimeout(() => {
                (<any>this.$refs.imgViewer).show(self.images, 0);
            }, 500);
        }
    }


    /**
     * //Manage Product Id
     * Open Modal box for file management
     */
    openProductIdModal(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        OrderModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IOrderFullResponse>>) => {
                OrderModule.SET_VIEW_MODEL(response.data.data as IOrderFullResponse);
                HTMLClassModule.removeBodyClassName("page-loading");
                (<any>this.$refs.manageproductidmodal).manageFileModal();
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }


    created(): void {

        HTMLClassModule.addBodyClassName("page-loading");

        /*Single Master : Start */
        CommonModule.getAll({
            apiName: "users",
            pagination: { page: 1 },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                UserModule.SET_USER_LIST(
                    <IUserLightResponse[]>response.data.data
                );
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

      /*Single Master : End */
    }
}
export default Order;
