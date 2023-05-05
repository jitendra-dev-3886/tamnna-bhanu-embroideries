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
    IHomeBannerModel,
    IHomeBannerParams,
    IHomeBannerFullResponse,
    IHomeBannerLightResponse,
} from "../../assets/types/homebanner";

import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {

        homeBannerList: [],
        model: {
           name: "",
           banner_status: "1",
            featured_image: "",
        },
        viewModel: {
            id: "",
            name: "",
            banner_status: "1",
            featured_image: "",
        },
        editId: 0,
    };
}
export interface IHomeBanner {
    homeBannerList: IHomeBannerLightResponse[];
    model: IHomeBannerModel;
    viewModel: IHomeBannerFullResponse;
    editId: IHomeBannerParams["editId"];
}

@Module({
    dynamic: true,
    store,
    name: "homebanner",
    namespaced: true,
    preserveState: isExistInLocalStorage("homebanner"),
})
class HomeBanner extends VuexModule implements IHomeBanner {
    public homeBannerList: IHomeBannerLightResponse[] = getEmptyState().homeBannerList;

    public model: IHomeBannerModel = getEmptyState().model;
    public viewModel: IHomeBannerFullResponse = getEmptyState().viewModel;
    public editId: IHomeBannerParams["editId"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_MODEL(param: IHomeBannerModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: IHomeBannerFullResponse) {
        this.viewModel = param;
    }



    @Mutation
    SET_HOMEBANNER_LIST(payload: IHomeBannerLightResponse[]) {
        this.homeBannerList = payload;
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
     * Used for add homebanner
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: IHomeBannerParams
    ): Promise<AxiosResponse<ResponseResult<IHomeBannerFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}homebanners`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IHomeBannerFullResponse>
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
     * Used for edit homebanner
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: IHomeBannerParams
    ): Promise<AxiosResponse<ResponseResult<IHomeBannerFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.put(`${this.baseUrl}homebanners/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IHomeBannerFullResponse>
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
     * Used for delete homebanner
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}homebanners/${param}`)
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
            HTTP.post(`${this.baseUrl}homebanners-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular homebanner record
     */
    @Action({ rawError: true })
    getById(
        param: string
    ): Promise<AxiosResponse<ResponseResult<IHomeBannerFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}homebanners/${param}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IHomeBannerFullResponse>
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
            HTTP.post(`${this.baseUrl}homebanners-import-bulk`, param)
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

export const HomeBannerModule = getModule(HomeBanner);
