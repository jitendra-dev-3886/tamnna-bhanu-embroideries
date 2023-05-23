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
    ICategoryModel,
    ICategoryParams,
    ICategoryFullResponse,
    ICategoryLightResponse,
} from "../../assets/types/category";
import { AxiosResponse } from "axios";
import { IProductGalleryParams } from "../../assets/types/product";

// mutation types
function getEmptyState() {
    return {
        categoryList: [],
        model: {
            name: "",
            description: "",
            featured_image: "",
            parent_id: "",
        },
        viewModel: {
            id: "",
            name: "",
            description: "",
            featured_image: "",
            parent_id: "",
        },
        editId: 0,
        parentCategoryList: [],
    };
}
export interface ICategory {
    categoryList: ICategoryLightResponse[];
    model: ICategoryModel;
    viewModel: ICategoryFullResponse;
    editId: ICategoryParams["editId"];
    parentCategoryList: ICategoryFullResponse[];
}

@Module({
    dynamic: true,
    store,
    name: "category",
    namespaced: true,
    preserveState: isExistInLocalStorage("category"),
})
class Category extends VuexModule implements ICategory {
    public categoryList: ICategoryLightResponse[] =
        getEmptyState().categoryList;

    public model: ICategoryModel = getEmptyState().model;
    public viewModel: ICategoryFullResponse = getEmptyState().viewModel;
    public editId: ICategoryParams["editId"] = getEmptyState().editId;
    public parentCategoryList: ICategoryFullResponse[] =
        getEmptyState().parentCategoryList;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_MODEL(param: ICategoryModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: ICategoryFullResponse) {
        this.viewModel = param;
    }

    @Mutation
    SET_CATEGORY_LIST(payload: ICategoryLightResponse[]) {
        this.categoryList = payload;
    }

    @Mutation
    SET_PARENT_CATEGORY_LIST(payload: ICategoryFullResponse[]) {
        this.parentCategoryList = payload;
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
     * Used for add category
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: ICategoryParams
    ): Promise<AxiosResponse<ResponseResult<ICategoryFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}categories`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse>
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
     * Used for edit category
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: ICategoryParams
    ): Promise<AxiosResponse<ResponseResult<ICategoryFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.put(`${this.baseUrl}categories/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse>
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
     * Used for delete category
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}categories/${param}`)
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
            HTTP.post(`${this.baseUrl}categories-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for edit image
     * @param param
     */
    @Action({ rawError: true })
    updateFeatureImg(
        param: IProductGalleryParams
    ): Promise<AxiosResponse<ResponseResult<ICategoryFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(
                `${this.baseUrl}categories-update-image/${param.editId}`,
                param.images
            )
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse>
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
     * Used to get a particular category record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<ICategoryFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}categories/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse>
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
     * Used to get a particular category record
     */
    @Action({ rawError: true })
    getParentCategoryList(): Promise<
        AxiosResponse<ResponseResult<ICategoryFullResponse[]>>
    > {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}parent-categories`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse[]>
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
     * Used to get a particular category record
     */
    @Action({ rawError: true })
    getSubCategoryList(
        param: string[]
    ): Promise<AxiosResponse<ResponseResult<ICategoryFullResponse[]>>> {
        return new Promise((resolve, reject) => {
            debugger;
            HTTP.get(`${this.baseUrl}subcategories`, param)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICategoryFullResponse[]>
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
            HTTP.post(`${this.baseUrl}categories-import-bulk`, param)
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

export const CategoryModule = getModule(Category);
