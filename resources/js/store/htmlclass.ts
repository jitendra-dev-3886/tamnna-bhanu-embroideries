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
export interface IHtmlClass {
    classes: any;
}

@Module({
    dynamic: true,
    store,
    name: "htmlClass",
    namespaced: true,
    preserveState: isExistInLocalStorage("htmlClass"),
})
class HtmlClass extends VuexModule implements IHtmlClass {
    public classes: any = {};

    @Mutation
    SET_CLASSNAME_BY_POSITION(payload) {
        const { position, className } = payload;
        if (!this.classes[position]) {
            this.classes[position] = [];
        }
        this.classes[position].push(className);
    }

    get getClasses() {
        return (position?) => {
            if (typeof position !== "undefined") {
                return this.classes[position];
            }
            return this.classes;
        };
    }

    @Action
    addBodyClassName(className) {
        document.body.classList.add(className);
    }

    @Action
    removeBodyClassName(className) {
        document.body.classList.remove(className);
    }

    @Action
    addClassName(payload) {
        this.SET_CLASSNAME_BY_POSITION(payload);
    }
}

export const HTMLClassModule = getModule(HtmlClass);
