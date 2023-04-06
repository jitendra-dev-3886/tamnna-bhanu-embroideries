/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue"; 
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";
import CartViewModal from "./CartViewModal.vue";
import { HTMLClassModule } from "../../../store/htmlclass"; 
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { UserModule } from "@/store/user";
import { IUserLightResponse } from "../../../../assets/types/user";
import { ProductModule } from "@/store/product";
import { IProductLightResponse } from "../../../../assets/types/product";
import { CartModule } from "../../../store/cart";

import { BatchRequestModule } from "../../../store/batch-request";
import {
    ITableHeaders, 
    IFilterModel,
} from "../../../../assets/types/table";

import {  
    ICartBatchResponse,
    ICartFullResponse,
} from "../../../../assets/types/cart";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";
import {
    IConfirmationProps,
    IDeleteProps,
    IExportProps,
    IImportProps,
    IParamProps,   
    ResponseResult, 
    IBatchReqUrls,
} from "../../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        CartViewModal,
        Import,  
    },
})
class Cart extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "carts";

    headers: ITableHeaders[] = [
        { text: 'User', value: 'user_id' },
        { text: 'Product', value: 'product_id' },
        { text: 'Quantity', value: 'quantity' },
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
        store: "cart", //as per Module Name
        modelName: "cart", //as per Module Name
        multiple: false,
    };
    
    exportProps: IExportProps = {
        ids: "",        
        fileName: "Cart",
        apiName: "carts-export",                
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    user_id="";
            product_id="";
    filterMenu= false;
    filterModel: IFilterModel = {};
        
    
    cartViewModal = false;

    
    get userList(): IUserLightResponse[] {
        return UserModule.userList;
    }
    get productList(): IProductLightResponse[] {
        return ProductModule.productList;
    }
    

    /**
     * Redirect to add cart page
     * */
    addCart(): void {
        this["$router"].push("/masters/cart/add");
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
     * Delete Cart
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "cart";
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
        this.deleteProps.store = "cart";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }

    onEdit(id: string | number): void {
        CartModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/cart/edit/" + id);
    }
    /**
     * View Cart
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CartModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<ICartFullResponse>>) => {
                CartModule.SET_VIEW_MODEL(
                    response.data.data as ICartFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.cartViewModal = true;
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
       
       if (this.product_id != '') {
         filter.product_id = [this.product_id];
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
      this.product_id = "";
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

    
    

    created(): void {
        
        HTMLClassModule.addBodyClassName("page-loading");

      /* Batch Requests : Start */
      const api = "getBatchRequests";
      const requestArray: IBatchReqUrls[] = [
            {
                url: "api/v1/users?is_light=1",
                request_id: "userList",
            },
            {
                url: "api/v1/products?is_light=1",
                request_id: "productList",
            },
      ];
      BatchRequestModule[api](requestArray).then(
        (response: unknown) => {
            const castedResponse = response as ICartBatchResponse;
            HTMLClassModule.removeBodyClassName("page-loading");
        
                UserModule.SET_USER_LIST(
                    castedResponse.userList.data as IUserLightResponse[]
                );
                ProductModule.SET_PRODUCT_LIST(
                    castedResponse.productList.data as IProductLightResponse[]
                );
      },
        (error) => {
            HTMLClassModule.removeBodyClassName("page-loading");
            this.showDialog(this.getAPIErrorMessage(error.response));
        }
      );
      /* Batch Requests : End */
    }
}
export default Cart;
