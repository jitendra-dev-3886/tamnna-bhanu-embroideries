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
    IPagination,
    ResponseResult,
    IDeleteProps
} from "../../assets/types/common";
import {
    isExistInLocalStorage
} from "@/filters/common";
import {
    ICustomerModel,

    ICustomerParams

} from "../../assets/types/customer";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {

      customerList: [],
        model: {
            id:"",
            name: " ",
            company_name:" ",
            city: " ",
            mobileno: " ",
            user_status:" "
        },
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            descending: "default",
            filter: "",
        },
        editId: 0,
        defaultRouteUrl:"",
        remember_me: "0",
        tableData: {
            data: [],
        },

    };
}
export interface ICustomer {

    model: ICustomerModel;
    editId: ICustomerParams["editId"];
    pagination: IPagination;
    tableData: ResponseResult<ICustomerModel[]>;
    remember_me: ICustomerParams["remember_me"];
    defaultRouteUrl: string;
}

@Module({
    dynamic: true,
    store,
    name: "customer",
    namespaced: true,
    preserveState: isExistInLocalStorage("customer"),
})
class Customer extends VuexModule implements ICustomer {

    public pagination: IPagination = getEmptyState().pagination;
    public model: ICustomerModel = getEmptyState().model;
    //public customerList: ICustomerModel=getEmptyState().model;
    public tableData: ResponseResult<ICustomerModel[]> =
        getEmptyState().tableData;

    public editId: ICustomerParams["editId"] = getEmptyState().editId;
    public remember_me: ICustomerParams["remember_me"] =
        getEmptyState().remember_me;
    public defaultRouteUrl: string = getEmptyState().defaultRouteUrl;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string) {
        this.editId = payload;
    }


    @Mutation
    SET_MODEL(param: ICustomerModel) {
        this.model = param;
    }

    /*@Mutation
    SET_CUSTOMER_LIST(payload: ICustomerModel[]) {
        this.customerList = payload;
    }*/

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;

    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
    }



    /**
     * Used for adding customer
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: ICustomerParams
    ): Promise<AxiosResponse<ResponseResult<ICustomerModel>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}customers`, param.editId)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICustomerModel>
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
     * Used for editing customer
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: ICustomerParams
    ): Promise<AxiosResponse<ResponseResult<ICustomerModel>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}customers/${param}`, param.editId)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICustomerModel>
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
     * Used for deleting customer
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}customers/${param}`)
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
            HTTP.post(`${this.baseUrl}customers-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular customer record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<ICustomerModel>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}customers/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICustomerModel>
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
     * Used to change the existing status of the customer
     */
     @Action({ rawError: true })
     setCustomerStatus(
         customerStatus: ICustomerParams
     ): Promise<AxiosResponse<ResponseResult<ICustomerModel>>> {
         return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}user_status/verify`, customerStatus)
                .then(
                     (
                         response: AxiosResponse<
                             ResponseResult<ICustomerModel>
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
     * Used for import zip functionality (upload file)
     * @param param
     */
     @Action({ rawError: true })
     importZip(
        param: IImportReqParams
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(this.baseUrl + "customers-upload-zip", param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

}

export const CustomerModule = getModule(Customer);
