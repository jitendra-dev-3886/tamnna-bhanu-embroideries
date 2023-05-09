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
    IDeleteProps,
} from "../../assets/types/common";
import { isExistInLocalStorage } from "@/filters/common";
import {
    IProductModel,
    IProductParams,
    IProductFullResponse,
    IProductLightResponse,
    IProductGalleries,
    IProductGalleryParams,
} from "../../assets/types/product";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {
        galleryList: [],
        productList: [],
        model: {
            name: "",
            price: "",
            description: "",
            item_code: "",
            category_id: [],
            available_status: "1",
            stock: "",
            featured_image: "",
            product_galleries: [],
        },
        viewModel: {
            id: "",
            name: "",
            price: "",
            description: "",
            item_code: "",
            category_id: [],
            available_status: "",
            available_status_text: " ",
            stock: "",
            featured_image: "",
            product_galleries: [],
            category_detail: [],
            category: {
                id: "",
                name: "",
                description: "",
                featured_image: "",
            },
        },
        editId: 0,
    };
}
export interface IProduct {
    productList: IProductLightResponse[];
    model: IProductModel;
    galleryList: IProductGalleries[];
    viewModel: IProductFullResponse;
    editId: IProductParams["editId"];
}

@Module({
    dynamic: true,
    store,
    name: "product",
    namespaced: true,
    preserveState: isExistInLocalStorage("product"),
})
class Product extends VuexModule implements IProduct {
    public productList: IProductLightResponse[] = getEmptyState().productList;

    public galleryList: IProductGalleries[] = getEmptyState().galleryList;
    public model: IProductModel = getEmptyState().model;
    public viewModel: IProductFullResponse = getEmptyState().viewModel;
    public editId: IProductParams["editId"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_MODEL(param: IProductModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: IProductFullResponse) {
        this.viewModel = param;
        this.galleryList = this.viewModel.product_galleries;
    }

    @Mutation
    SET_GALLERY_LIST(payload: IProductGalleries[]) {
        this.galleryList = payload;
    }

    @Mutation
    SET_PRODUCT_LIST(payload: IProductLightResponse[]) {
        this.productList = payload;
    }

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
        this.galleryList = getEmptyState().galleryList;
    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }

    /**
     * Used for add product
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: IProductParams
    ): Promise<AxiosResponse<ResponseResult<IProductFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}products`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IProductFullResponse>
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
     * Used for edit product
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: IProductParams
    ): Promise<AxiosResponse<ResponseResult<IProductFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}products/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IProductFullResponse>
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
     * Used for delete product
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}products/${param}`)
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
            HTTP.post(`${this.baseUrl}products-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular product record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<IProductFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}products/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IProductFullResponse>
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
     * Used for edit product
     * @param param
     */
     @Action({ rawError: true })
     updateProductGallery(
        param: IProductGalleryParams
     ): Promise<AxiosResponse<ResponseResult<IProductFullResponse>>> {
         return new Promise((resolve, reject) => {
             HTTP.post(`${this.baseUrl}product-update-gallery/${param.editId}`, param.images)
                 .then(
                     (
                         response: AxiosResponse<
                             ResponseResult<IProductFullResponse>
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
     *  Manage Gallery
     * Used for delete gallery image
     * @param param
     */
    @Action({ rawError: true })
    deleteGallery(
        param: string
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}products-gallery/${param}`)
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
            HTTP.post(`${this.baseUrl}products-import-bulk`, param)
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
            HTTP.post(this.baseUrl + "products-upload-zip", param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }
}

export const ProductModule = getModule(Product);
