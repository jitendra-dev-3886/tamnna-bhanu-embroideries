import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import { isExistInLocalStorage } from "@/filters/common";

export interface IError {
    showErrorDialog: boolean;
    errorArray: string | [];
}

@Module({
    dynamic: true,
    store,
    name: "error",
    namespaced: true,
    preserveState: isExistInLocalStorage("error"),
})
class Error extends VuexModule implements IError {
    public showErrorDialog = false;
    public errorArray: string | [] = "";

    @Mutation
    SET_ERRORS(payload: string | []) {
        this.errorArray = payload;
    }

    @Mutation
    SET_DISPLAY_DIALOG(payload: boolean) {
        this.showErrorDialog = payload;
    }

    @Action
    setErrors(payload: string | []) {
        this.SET_ERRORS(payload);
    }

    @Action
    willDisplayDialog(payload: boolean) {
        this.SET_DISPLAY_DIALOG(payload);
    }
}

export const ErrorModule = getModule(Error);
