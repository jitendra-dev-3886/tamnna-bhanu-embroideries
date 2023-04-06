import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import { isExistInLocalStorage } from "@/filters/common";
// mutation types
export interface ISnackbar {
    msg: string;
    snackbar: boolean;
}
@Module({
    dynamic: true,
    store,
    name: "snackbar",
    namespaced: true,
    preserveState: isExistInLocalStorage("snackbar"),
})
class Snackbar extends VuexModule implements ISnackbar {
    public msg = "";
    public snackbar = false;

    @Mutation
    private SET_MSG(payload: string) {
        this.msg = payload;
        this.snackbar = true;
    }

    @Mutation
    private CLEAR_STORE() {
        this.msg = "";
        this.snackbar = false;
    }

    @Action
    public setMsg(payload: string) {
        this.SET_MSG(payload);
    }

    @Action
    public clearStore() {
        this.CLEAR_STORE();
    }
}

export const SnackbarModule = getModule(Snackbar);
