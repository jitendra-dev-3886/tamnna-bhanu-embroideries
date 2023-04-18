import Vue from "vue";
import Vuex from "vuex";
import VuexPersist from "vuex-persist";

// theme store file
import { IConfig } from "@/store/config";
import { IForgotPassword } from "@/store/forgot-password";
import { IUser } from "@/store/user";
import { IHtmlClass } from "@/store/htmlclass";
import { ISnackbar } from "@/store/snackbar";
import { IPermission } from "@/store/permission";
import { IError } from "@/store/error";
import { IRole } from "@/store/role";
import { IBatchRequest } from "@/store/batch-request";
import { IBreadcrumb } from "@/store/breadcrumbs";
import { IChangePassword } from "@/store/change-password";
import { IActivityLog } from "@/store/activity-log";

import {ICustomer} from "@/store/customer";
import { IHomeBanner } from "./homebanner";

import { ICategory } from "@/store/category";
import { IProduct } from "@/store/product";
import { IOrder } from "@/store/order";
import { ICart } from "@/store/cart";

Vue.use(Vuex);

const vuexPersist = new VuexPersist({
    key: "tamanna-bhanu-embroideries-b2b-mcommerce",
    storage: localStorage,
});

export interface IRootState {
    user: IUser;
    config: IConfig;
    htmlClass: IHtmlClass;
    snackbar: ISnackbar;
    permission: IPermission;
    forgotPassword: IForgotPassword;
    error: IError;
    role: IRole;
    batchRequest: IBatchRequest;
    changePassword: IChangePassword;
    breadcrumb: IBreadcrumb;
    activityLog: IActivityLog;
    category: ICategory;
    product: IProduct;
    order: IOrder;
    cart: ICart;
    customer:ICustomer;
    homebanner:IHomeBanner
}
export default new Vuex.Store<IRootState>({
    plugins: [vuexPersist.plugin],
});
