import objectPath from "object-path";
import merge from "deepmerge";
import config from "./layout.config.json";
import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import { isExistInLocalStorage } from "@/filters/common";
import { IHtmlConfig } from "../../assets/types/config";

// mutation types
export interface IConfig {
    config: IHtmlConfig;
    initial: IHtmlConfig;
}

@Module({
    dynamic: true,
    store,
    name: "config",
    namespaced: true,
    preserveState: isExistInLocalStorage("config"),
})
class Config extends VuexModule implements IConfig {
    public config: IHtmlConfig = config;
    public initial: IHtmlConfig = config;

    @Mutation
    SET_LAYOUT_CONFIG(payload) {
        this.config = payload;
    }

    @Mutation
    RESET_LAYOUT_CONFIG() {
        this.config = { ...this.initial };
    }

    @Mutation
    OVERRIDE_LAYOUT_CONFIG() {
        this.config = {
            ...this.initial,
            ...JSON.parse(localStorage.getItem("config") || "{}"),
        };
        this.initial = {
            ...this.initial,
            ...JSON.parse(localStorage.getItem("config") || "{}"),
        };
    }

    @Mutation
    OVERRIDE_PAGE_LAYOUT_CONFIG(payload) {
        this.config = merge(this.config, payload);
    }

    /**
     * Get config from layout config
     * @returns {function(path, defaultValue): *}
     */
    get layoutConfig() {
        return (path?, defaultValue?) => {
            return objectPath.get(this.config, path, defaultValue);
        };
    }

    /**
     * Set and replace the whole config
     * @param payload
     */
    @Action
    setLayoutConfig(payload) {
        this.SET_LAYOUT_CONFIG(payload);
    }

    /**
     * Reset the config at the initial state
     */
    @Action
    resetLayoutConfig() {
        this.RESET_LAYOUT_CONFIG();
    }

    /**
     * Reset the config using saved config in the cache by the layout builder
     */
    @Action
    overrideLayoutConfig() {
        this.OVERRIDE_LAYOUT_CONFIG();
    }

    /**
     * Override config by page level
     * @param payload
     */
    @Action
    overridePageLayoutConfig(payload) {
        this.OVERRIDE_PAGE_LAYOUT_CONFIG(payload);
    }
}

export const ConfigModule = getModule(Config);
