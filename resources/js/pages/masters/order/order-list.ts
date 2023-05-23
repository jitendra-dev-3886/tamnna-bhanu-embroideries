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
import { OrderModule } from '../../../store/order';


import {
    ITableHeaders,
    IFilterModel,
} from "../../../../assets/types/table";

import {

    IOrderFullResponse
} from "../../../../assets/types/order";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";
//import { IOrderFullResponse } from '../../../../assets/types/order';
import CommonDateMethod from '../../../mixins/common-date-methods';
import PeriodFilter from '../../../components/PeriodFilter.vue';
import {
    IConfirmationProps,
    IDeleteProps,
    IExportProps,
    IImportProps,
    IParamProps,
    ResponseResult,
    IDatePeriodFilter

} from "../../../../assets/types/common";
import { format } from "date-fns";
import { CustomerModule } from "@/store/customer";
import { ICustomerModel } from "../../../../assets/types/customer";
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
        PeriodFilter
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
       {text:'Products',value:'order_products.product.name'},
        { text: 'Gst', value: 'gst' },
        { text: 'Payment Amount', value: 'payment_amount' },
        {text:'Order Date',value:'created_at'},
     //  { text: 'Order Status', value: 'order_status_text' },
        { text: 'Actions', value: 'actions', sortable: false },
            ];

    customSortableKeys = {order_status_text: "order_status",};
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
    selectedUser="";
    filterMenu= false;
    orderDate="";
    datemenu=false;
    filterModel: IFilterModel = {};
    customers=[];
    date_model: IDatePeriodFilter = {
        user_id: ["0"],
        module: ["0"],
        period_type: "",
        start_date: format(new Date(), "yyyy-MM-dd"),
        end_date: format(new Date(), "yyyy-MM-dd"),
        is_all_date: "1",
        is_all_module: "1",
        is_all_user: "1",
    };

    images: { thumbnail: string; source: string }[] = [];
    orderViewModal = false;



    get viewModel():IOrderFullResponse{
        return OrderModule.viewModel;

    }

    get userList():IUserLightResponse[]{
        return UserModule.userList;
    }

    /*get prodDetailModel():IOrderProducts{

        return OrderModule.prodDetail;

    }
   /* get productIdList(): IOrderProducts[] {
        return OrderModule.product_idList;
    }*/

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

    /*
      Delete Order
      @param id

    /*deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "order";
        this.confirmation.title = this["$getConst"]("DELETE_TITLE");
        this.confirmation.description = this["$getConst"]("WARNING");
        this.modalOpen = true;
    }*/

    /**
     * Multiple Delete
     */
    /*multipleDelete(): void {
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
   /* onView(id: string): void {
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
    }*/
    filterByPeriod(filter: IFilterModel): IFilterModel {
        debugger;
        if (this.date_model.period_type == "10") {
            filter.is_all_date = "1";
        } else {
            filter.is_all_date = "0";
        }
        if (filter.is_all_user == "1" || this.date_model.user_id[0] == "0") {
            delete filter.causer_id;
        } else {
            filter.causer_id = this.date_model.user_id.filter(
                (item, index) => item != null && item != undefined
            );
            delete filter.is_all_user;
        }
        if (filter.is_all_module == "1" || this.date_model.module[0] == "0") {
            delete filter.log_name;
        } else {
            filter.log_name = this.date_model.module.filter(
                (item, index) => item != null && item != undefined
            );
            delete filter.is_all_module;
        }

        if (
            this.date_model.is_all_date == "1" &&
            this.date_model.period_type == "10"
        ) {
            delete filter.period_type;
            delete filter.created_at;
            delete filter.is_all_date;
        } else if (this.date_model.period_type == "0") {
            delete filter.is_all_date;
            filter.created_at = `${this.getFilterDateFormat(
                this.date_model.start_date
            )}to${this.customDayMonthDate(
                this.date_model.end_date,
                1,
                "days",
                true
            )}`;
        } else if (this.date_model.period_type == "1") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                3,
                "months",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.date_model.period_type == "2") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                6,
                "months",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.date_model.period_type == "3") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                1,
                "years",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.date_model.period_type == "4") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                2,
                "years",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (
            this.date_model.period_type != "0" &&
            this.date_model.period_type != "10"
        ) {
            delete filter.is_all_date;
            delete filter.created_at;
        }
        return filter;
    }


    onCancelFilter(): void {
        this.onModalClear("order", "CLEAR_FILTER_STORE");
        this.resetFilter();
    }

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
       let filter: IFilterModel = {};

       if (this.user_id != '') {
         filter.user_id = [this.user_id];
       }
       debugger;
       filter = this.filterByPeriod(filter);

       this.filterModel = filter;
       this.refresh();
       this.filterMenu = false;
     }

    /**
     * Reset Filter*/

    resetFilter(): void {
      this.user_id = "";
      this.date_model.period_type = "10";
        this.date_model.start_date = format(new Date(), "yyyy-MM-dd");
        this.date_model.end_date = format(new Date(), "yyyy-MM-dd");
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
   /* openProductId(row: IOrderFullResponse): void {
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
    }*/


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
            apiName: "customers",
            pagination: { isLight: true },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                CustomerModule.SET_CUSTOMER_LIST(
                    <ICustomerModel[]>response.data.data
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
