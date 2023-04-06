import HTTP from "../common_services/api-services";
import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule
} from "vuex-module-decorators";
import {
    ResponseResult,
    IPagination,
    IImportCSVLogResponse
} from "../../assets/types/common";
import { isExistInLocalStorage, getSortType } from "@/filters/common";
import { AxiosResponse } from "axios";

// mutation types
function getEmptyState() {
    return {
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            descending: "default",
            filter: ""
        },
        tableData: { data: [] }
    };
}
export interface ICommon {
    pagination: IPagination;
    tableData: ResponseResult<unknown>;
}

@Module({
    dynamic: true,
    store,
    name: "common",
    namespaced: true,
    preserveState: isExistInLocalStorage("common")
})
class Common extends VuexModule implements ICommon {
    public pagination: IPagination = getEmptyState().pagination;
    public tableData: ResponseResult<unknown> = getEmptyState().tableData;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    public SET_PAGINATION(pagination: IPagination) {
        this.pagination = pagination;
    }

    @Mutation
    public SET_TABLE_DATA(tableData: ResponseResult<unknown>) {
        this.tableData = tableData.data as { data: [] };
    }

    /**
     * Used to get list of records
     * @param param
     */
    @Action
    getAll(param: {
        apiName: string;
        pagination: IPagination;
        additionalParams?: { [index: string]: string | boolean };
    }): Promise<AxiosResponse<ResponseResult<unknown>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(
                `${this.baseUrl}${param.apiName}?page=${
                    param.pagination.page ? param.pagination.page : 1
                }&per_page=${
                    param.pagination.limit ? param.pagination.limit : ""
                }&search=${
                    param.pagination.query ? param.pagination.query : ""
                }&filter=${
                    param.pagination.filter ? param.pagination.filter : ""
                }&is_light=${
                    param.pagination.isLight ? param.pagination.isLight : ""
                }&sort=${
                    param.pagination.orderBy ? param.pagination.orderBy : ""
                }&order_by=${getSortType(param.pagination.descending)}`,
                {
                    params: param.additionalParams ? param.additionalParams : {}
                }
            )
                .then((response: AxiosResponse<ResponseResult<unknown>>) => {
                    resolve(response);
                })
                .catch(e => {
                    reject(e);
                });
        });
    }

    /**
     * Used for export functionality
     * @param param
     */
    @Action
    export({
        apiName,
        param
    }: {
        apiName: string;
        param: IPagination;
    }): Promise<AxiosResponse<ResponseResult<string>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(
                `${this.baseUrl}${apiName}?page=${
                    param.page ? param.page : 1
                }&filter=${param.filter ? param.filter : ""}&search=${
                    param.query ? param.query : ""
                }&sort=${
                    param.orderBy ? param.orderBy : ""
                }&order_by=${getSortType(param.descending)}`
            )
                .then((response: AxiosResponse<ResponseResult<string>>) => {
                    resolve(response);
                })
                .catch(e => {
                    reject(e);
                });
        });
    }

    @Action
    getByImportId(
        id: string
    ): Promise<AxiosResponse<ResponseResult<IImportCSVLogResponse[]>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}import-csv-log/${id}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IImportCSVLogResponse[]>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch(e => {
                    reject(e);
                });
        });
    }
}

export const CommonModule = getModule(Common);
