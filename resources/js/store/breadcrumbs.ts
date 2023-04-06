import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule
} from "vuex-module-decorators";
import { isExistInLocalStorage } from "@/filters/common";
import { IObject } from "../../assets/types/common";

export interface IBreadcrumb {
    listBreadcrumbs: IObject[];
}

@Module({
    dynamic: true,
    store,
    name: "breadcrumb",
    namespaced: true,
    preserveState: isExistInLocalStorage("breadcrumb")
})
class Breadcrumb extends VuexModule implements IBreadcrumb {
    public listBreadcrumbs: IObject[] = [];

    /**
     * Get list of listBreadcrumbs for current page
     * @returns {*}
     */
    get breadcrumbs(): IObject[] {
        return this.listBreadcrumbs;
    }

    /**
     * Get the page title
     * @returns {*}
     */
    get pageTitle(): string {
        const last = this.listBreadcrumbs[this.listBreadcrumbs.length - 1];
        if (last && last.title) {
            return <string>last.title;
        }
        return "";
    }

    @Mutation
    APPEND_BREADCRUM(payload) {
        this.listBreadcrumbs = [...this.breadcrumbs, payload];
    }

    @Mutation
    SET_BREADCRUMB(payload) {
        this.listBreadcrumbs = payload;
    }

    /**
     * Set the breadcrumbs list
     * @param payload
     */
    @Action
    setBreadcrumb(payload) {
        this.SET_BREADCRUMB(payload);
    }

    /**
     * Add breadcrumb
     * @param state
     * @param payload
     */
    @Action
    addBreadcrumb(state, payload) {
        if (typeof payload === "object") {
            payload.forEach(item => this.APPEND_BREADCRUM(item));
        } else {
            this.APPEND_BREADCRUM(payload);
        }
    }
}

export const BreadcrumbModule = getModule(Breadcrumb);
