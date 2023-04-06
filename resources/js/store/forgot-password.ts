import store from "./store";
import { VuexModule, Module, Action, getModule } from "vuex-module-decorators";
import HTTP from "../common_services/api-services";
import { isExistInLocalStorage } from "@/filters/common";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../assets/types/common";
import { IForgotPasswordModel } from "../../assets/types/auth";

export interface IForgotPassword {
    baseUrl: string;
}

@Module({
    dynamic: true,
    store,
    name: "forgotPassword",
    namespaced: true,
    preserveState: isExistInLocalStorage("forgotPassword"),
})
class ForgotPassword extends VuexModule implements IForgotPassword {
    baseUrl = process.env.MIX_API_BASE_URL;
    /**
     * Used for send forgot password mail
     * @param param
     */
    @Action({ rawError: true })
    sendForgotPasswordEmail(
        param: IForgotPasswordModel
    ): Promise<AxiosResponse<ResponseResult<string>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}forgot-password`, param)
                .then((response) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }
}

export const ForgotPasswordModule = getModule(ForgotPassword);
