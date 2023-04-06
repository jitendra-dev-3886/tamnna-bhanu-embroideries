import store from "./store";
import { VuexModule, Module, Action, getModule } from "vuex-module-decorators";
import qs from "qs";
import HTTP from "../common_services/api-services";
import { isExistInLocalStorage } from "@/filters/common";
import { UserModule } from "@/store/user";
import {
    IBatchReqUrls,
    IBatchReqUrlsWithNoAuth,
} from "../../assets/types/common";
import { AxiosResponse } from "axios";

export interface IBatchRequest {
    baseUrl: string;
    authBaseUrl: string;
}

@Module({
    dynamic: true,
    store,
    name: "batchRequest",
    namespaced: true,
    preserveState: isExistInLocalStorage("batchRequest"),
})
class BatchRequest extends VuexModule implements IBatchRequest {
    public baseUrl = `${process.env.MIX_API_BASE_URL}batch-request`;
    public authBaseUrl = `${process.env.MIX_API_BASE_URL}auth-batch-request`;

    @Action({ rawError: true })
    getBatchRequests(
        param: IBatchReqUrls[] | IBatchReqUrlsWithNoAuth
    ): Promise<unknown> {
        let noAuth = false;
        if (!Array.isArray(param)) {
            if (Object.hasOwnProperty.call(param, "noAuth") && param.noAuth) {
                noAuth = true;
                param = param.urls;
            }
        }
        const apiUrl = UserModule.currentUserData?.authorization
            ? this.authBaseUrl
            : this.baseUrl;
        return new Promise((resolve, reject) => {
            HTTP.get(apiUrl, {
                params: {
                    request: param,
                    noAuth,
                },
                paramsSerializer: (params: IBatchReqUrls[]) =>
                    qs.stringify(params),
            })
                .then((response: AxiosResponse) => {
                    const obj = response.data.response;
                    Object.keys(obj).forEach((key) => {
                        const val = obj[key];
                        if (
                            !Object.prototype.hasOwnProperty.call(val, "data")
                        ) {
                            return reject(response.data.response);
                        }
                    });
                    resolve(obj);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }
}

export const BatchRequestModule = getModule(BatchRequest);
