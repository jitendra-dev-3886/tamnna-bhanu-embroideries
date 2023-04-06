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
    ICartModel,
    ICartParams,
    ICartFullResponse,
    ICartLightResponse,
} from "../../assets/types/cart";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {        
        
        cartList: [],
        model: {
           user_id: "",
            product_id: "",
            quantity: "",
        },
        viewModel: { 
            id: "",
            user_id: "",
            product_id: "",
            quantity: "", 
            user: {
                id: "",
                email: "",
                email_verified_at: "",
                role_id: "",
                role: [],
            },
            product: {
                id: "",
                name: "",
                price: "",
                description: "",
                item_code: "",
                category_id: "",
            category: {
                id: "",
                name: "",
                description: "",
                featured_image: "",
            },
                available_status: "",
                available_status_text: "",
                stock: "",
                featured_image: "",
                product_galleries: [],
            },
        },
        editId: 0,
    };
}
export interface ICart {    
    cartList: ICartLightResponse[];
    model: ICartModel;
    viewModel: ICartFullResponse;
    editId: ICartParams["editId"];
}

@Module({
    dynamic: true,
    store,
    name: "cart",
    namespaced: true,
    preserveState: isExistInLocalStorage("cart"),
})
class Cart extends VuexModule implements ICart {    
    public cartList: ICartLightResponse[] = getEmptyState().cartList;
    
    public model: ICartModel = getEmptyState().model;
    public viewModel: ICartFullResponse = getEmptyState().viewModel;
    public editId: ICartParams["editId"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_MODEL(param: ICartModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: ICartFullResponse) {
        this.viewModel = param;
    }

    

    @Mutation
    SET_CART_LIST(payload: ICartLightResponse[]) {
        this.cartList = payload;
    }

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }

    

    /**
     * Used for add cart
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: ICartParams
    ): Promise<AxiosResponse<ResponseResult<ICartFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}carts`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICartFullResponse>
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
     * Used for edit cart
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: ICartParams
    ): Promise<AxiosResponse<ResponseResult<ICartFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.put(`${this.baseUrl}carts/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICartFullResponse>
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
     * Used for delete cart
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}carts/${param}`)
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
            HTTP.post(`${this.baseUrl}carts-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular cart record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<ICartFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}carts/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICartFullResponse>
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
     * Used for import functionality (upload file)
     * @param param
     */
    @Action({ rawError: true })
    import(
        param: IImportReqParams
    ): Promise<AxiosResponse<ResponseResult<IImportResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}carts-import-bulk`, param)
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

        

}

export const CartModule = getModule(Cart);
