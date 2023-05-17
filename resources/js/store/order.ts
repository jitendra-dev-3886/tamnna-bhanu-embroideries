import HTTP from "../common_services/api-services";
import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import {
    IImportReqParams,
    IImportResponse,
    ResponseResult,
    IDeleteProps
} from "../../assets/types/common";
import {
    isExistInLocalStorage
} from "@/filters/common";
import {
    IOrderModel,
    IOrderParams,
    IOrderFullResponse,
    IOrderLightResponse,
    IOrderProducts,
} from "../../assets/types/order";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {
        orderList:[],

        model: {
            gst: "",
            id:"",
            order_products: {
                product:{
                name:"",
                quantity:"",
                price:""
                },
                categories:{
                    id:"",
                    name:"",
                    featured_image:""
                }
            },
            order_status: "",
            order_status_remark: "",
            order_status_text:"",
            payment_amount: "",
            quantity:"",
            user_id: "",
            user_name:"",
            user_remark: ""

        },
        viewModel: {
            id: "",
            user_id: "",
            quantity: "",
            gst: "",
            payment_amount: "",
            order_status: "",
            order_status_text: "",
            order_status_remark: "",
            user_remark: "",
            user_name:"",
            order_products: {
                product:{
                name:"",
                quantity:"",
                price:""
                },
                categories:{
                    id:"",
                    name:"",
                    description:"",
                    featured_image:""
                }
            },
            product_id:"",
            product_id_original: "",
            product_id_thumbnail: ""
         },
        prodDetail:{
            featured_image:"",
            id:"",
            order_id:"",
            price:"",
            order_products:[],
            categories:[],
            product_id:"",
            product_id_original: "",
            product_id_thumbnail: ""
        },

        editId: 0,
    };
}
export interface IOrder {
    orderList: IOrderLightResponse[];
    model: IOrderModel;
   // product_idList: IOrderProducts[];
    viewModel: IOrderFullResponse;
    editId: IOrderParams["editId"];
}

@Module({
    dynamic: true,
    store,
    name: "order",
    namespaced: true,
    preserveState: isExistInLocalStorage("order"),
})
class Order extends VuexModule implements IOrder {
    public orderList: IOrderLightResponse[] = getEmptyState().orderList;

   // public product_idList: IOrderProducts[] = getEmptyState().productIdList;
    public model: IOrderModel = getEmptyState().model;
    public viewModel: IOrderFullResponse = getEmptyState().viewModel;
    public prodDetail:IOrderProducts=getEmptyState().prodDetail;
    public editId: IOrderParams["editId"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_MODEL(param: IOrderModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: IOrderFullResponse) {

        this.viewModel = param;


    }

    @Mutation
    SET_PROD_DETAIL_MODEL(param: IOrderProducts) {

        this.prodDetail = param;

    }

    /*@Mutation
    SET_PRODUCT_ID_LIST(payload: IOrderProducts[]) {
        this.product_idList = payload;
    }*/

    @Mutation
    SET_ORDER_LIST(payload: IOrderLightResponse[]) {
        this.orderList = payload;
    }

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
        this.prodDetail=getEmptyState().prodDetail;
        //this.product_idList = getEmptyState().productIdList;
    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }



    /**
     * Used for add order
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: IOrderParams
    ): Promise<AxiosResponse<ResponseResult<IOrderFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}orders`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IOrderFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for edit order
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: IOrderParams
    ): Promise<AxiosResponse<ResponseResult<IOrderFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}orders/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IOrderFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for delete order
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}orders/${param}`)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for multiple delete
     * @param param
     */
    @Action({ rawError: true })
    multiDelete(
        param: IDeleteProps
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}orders-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular order record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<IOrderFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}orders/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IOrderFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }



    /**
     *  Manage Product Id
     * Used for delete product id image
     * @param param
     */
     @Action({ rawError: true })
     deleteProductId(
        param: string
     ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}orders-product-id/${param}`)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                resolve(response);
            })
            .catch((e) => {
                reject(e);
            });
        });
     }

    /**
     * Used for import functionality (upload file)
     * @param param
     */
    @Action({ rawError: true })
    import(
        param: IImportReqParams
    ): Promise<AxiosResponse<ResponseResult<IImportResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}orders-import-bulk`, param)
                .then(
                    (
                        response: AxiosResponse<ResponseResult<IImportResponse>>
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }


    /**
     * Used for import zip functionality (upload file)
     * @param param
     */
     @Action({ rawError: true })
     importZip(
        param: IImportReqParams
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(this.baseUrl + "orders-upload-zip", param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

}

export const OrderModule = getModule(Order);
