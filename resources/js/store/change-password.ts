import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    getModule,
    Mutation
} from "vuex-module-decorators";
import HTTP from "../common_services/api-services";
import { isExistInLocalStorage } from "@/filters/common";
import { IChangePasswordModel } from "../../assets/types/auth";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../assets/types/common";

const baseUrl = process.env.MIX_API_BASE_URL;

export interface IChangePassword {
    model: IChangePasswordModel;
}

@Module({
    dynamic: true,
    store,
    name: "changePassword",
    namespaced: true,
    preserveState: isExistInLocalStorage("changePassword")
})
class ChangePassword extends VuexModule implements IChangePassword {
    public model: IChangePasswordModel = {
        old_password: "",
        new_password: "",
        confirm_password: ""
    };

    @Mutation
    CLEAR_STORE() {
        this.model.old_password = "";
        this.model.new_password = "";
        this.model.confirm_password = "";
    }

    /**
     * change the password
     * @param param
     */
    @Action({ rawError: true })
    changePassword(
        param: IChangePasswordModel
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${baseUrl}change-password`, param)
                .then(response => {
                    resolve(response);
                })
                .catch(e => {
                    reject(e);
                });
        });
    }
}

export const ChangePasswordModule = getModule(ChangePassword);
