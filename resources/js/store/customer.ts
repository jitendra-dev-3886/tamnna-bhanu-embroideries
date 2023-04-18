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
    ICustomerModel,
    ICustomerFullResponse,
    ICustomerParams

} from "../../assets/types/customer";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {

      //  customerList: [],
        model: {
            name: " ",
            company_name:" ",
            city: " ",
            mobileno: " ",
        },
        viewModel:{
            name: " ",
            company_name:" ",
            city: " ",
            mobileno: " ",
            status:" ",
        },
        editId: 0,

    };
}
export interface ICustomer {

    model: ICustomerModel;
    //customerList:ICustomerModel[];
    viewModel: ICustomerFullResponse;
    editId: ICustomerParams["status"];
}

@Module({
    dynamic: true,
    store,
    name: "customer",
    namespaced: true,
    preserveState: isExistInLocalStorage("customer"),
})
class Customer extends VuexModule implements ICustomer {

    public model: ICustomerModel = getEmptyState().model;
    //public customerList: ICustomerModel=getEmptyState().model;
    public viewModel:ICustomerFullResponse=getEmptyState().viewModel;

    public editId: ICustomerParams["status"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
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
            HTTP.post(`${this.baseUrl}customers`, param.model)
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
            HTTP.post(`${this.baseUrl}customers/${param.status}`, param.model)
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
