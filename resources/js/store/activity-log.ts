import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import HTTP from "../common_services/api-services";
import { isExistInLocalStorage, getSortType } from "@/filters/common";
import { format } from "date-fns";
import {
    IModel,
    IModuleNames,
    IObjectModalData,
    IPeriodTypeItems,
    ITableModalData,
} from "../../assets/types/activity-log";
import {
    IObject,
    IPagination,
} from "../../assets/types/common";

// mutation types
function getEmptyState() {
    return {
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            ascending: 1,
            filter: "",
        },        
        moduleNames: [
            { id: "user", name: "User" },
            { id: "permission", name: "Permission" },
            { id: "role", name: "Role" },
            { id: "permission", name: "Permission" },
            { id: "role", name: "Role" },
            { id: "permission", name: "Permission" },
            { id: "role", name: "Role" },
            {id: 'permission', name: 'Permission',},
            {id: 'role', name: 'Role',},
            {id: 'category_product', name: 'Category Product',},
            {id: 'category', name: 'Category',},
            {id: 'product', name: 'Product',},
            {id: 'product_gallery', name: 'Product Gallery',},
            {id: 'order_status', name: 'Order Status',},
            {id: 'order', name: 'Order',},
            {id: 'order_product', name: 'Order Product',},
            {id: 'cart', name: 'Cart',},
        ],
        tableModalData: {
            tableData: [],
            col: "",
        },
        objectModalData: {
            modelData: {},
            col: "",
        },
        model: {
            created_at: format(new Date(), "yyyy-MM-dd"),
            log_name: "",
            description: "",
            is_address: "",
            causer_id: "",
            user_id: ["0"],
            module: ["0"],
            period_type: "10",
            start_date: format(new Date(), "yyyy-MM-dd"),
            end_date: format(new Date(), "yyyy-MM-dd"),
            is_all_date: "1",
            is_all_module: "1",
            is_all_user: "1",
        },
        periodTypeItems: [
            {
                enum: "10", //It Is given hardcoded to set Default ALL option
                name: "All Dates (Default)",
            },
            {
                enum: "0",
                name: "Custom",
            },
            {
                enum: "1",
                name: "Last 3 months",
            },
            {
                enum: "2",
                name: "Last 6 months",
            },
            {
                enum: "3",
                name: "Last 12 months",
            },
            {
                enum: "4",
                name: "Last 24 months",
            },
        ],
    };
}
export interface IActivityLog {    
    moduleNames: IModuleNames[];
    tableModalData: ITableModalData;
    objectModalData: IObjectModalData;
    model: IModel;
    periodTypeItems: IPeriodTypeItems[];
}

@Module({
    dynamic: true,
    store,
    name: "activityLog",
    namespaced: true,
    preserveState: isExistInLocalStorage("activityLog"),
})
class ActivityLog extends VuexModule implements IActivityLog {    
    moduleNames: IModuleNames[] = getEmptyState().moduleNames;
    tableModalData: ITableModalData = getEmptyState().tableModalData;
    objectModalData: IObjectModalData = getEmptyState().objectModalData;
    model: IModel = getEmptyState().model;
    periodTypeItems: IPeriodTypeItems[] = getEmptyState().periodTypeItems;
    baseUrl = process.env.MIX_API_BASE_URL;    

    @Mutation
    SET_TABLE_MODAL_DATA(payload) {
        this.tableModalData = payload;
    }

    @Mutation
    SET_OBJECT_MODAL_DATA(payload) {
        this.objectModalData = payload;
    }

    @Mutation
    CLEAR_STORE() {
        this.tableModalData = getEmptyState().tableModalData;
        this.objectModalData = getEmptyState().objectModalData;
        this.model = getEmptyState().model;
    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
    }
}

export const ActivityLogModule = getModule(ActivityLog);
