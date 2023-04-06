import ExportBtn from "@/components/ExportBtn.vue";
import PeriodFilter from "@/components/PeriodFilter.vue";
import CommonApis from "@/mixins/common-apis";
import { HTMLClassModule } from "@/store/htmlclass";
import { Component, Mixins } from "vue-property-decorator";
import { ActivityLogModule } from "@/store/activity-log";
import { UserModule } from "@/store/user";
import { CommonModule } from "@/store/common";
import { format } from "date-fns";
import {
    IUserLightResponse,
} from "../../../../assets/types/user";
import ActivityLogViewModal from "./ActivityLogViewModal.vue";
import { AxiosResponse } from "axios";
import {
    IConfirmationProps,
    IExportProps,
    IParamProps,
    IObject,
    ResponseResult
} from "../../../../assets/types/common";
import { IFilterModel, ITableHeaders } from "../../../../assets/types/table";
import {
    IModel,
    IModuleNames,
} from "../../../../assets/types/activity-log";
import ServerTable from "@/mixins/customtable/server-table";

@Component({
    components: {
        ExportBtn,
        PeriodFilter,
        ActivityLogViewModal,
    },
})
class ActivityLog extends Mixins(ServerTable, CommonApis) {
    activityLogViewModal = false;

    tab = "tab1";

    modalOpen = false;

    urlApi = "get-activities"; // set store name here to set/get pagination data and for access of actions/mutation via custom table for a particular module

    headers: ITableHeaders[] = [
        //Dynamic column names
        { text: "Date", value: "created_at" },
        { text: "User", value: "causer_name" },
        { text: "Module", value: "log_name" },
        { text: "Activity", value: "description" },
        { text: "IP Address", value: "ip_address", sortable: false },
        { text: "Action", value: "actions", sortable: false },
    ];

    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this["$getConst"]("BTN_CANCEL"),
        btnConfirmationText: this["$getConst"]("BTN_OK"),
    };

    exportProps: IExportProps = {
        ids: "",
        fileName: "ActivityLog",
        apiName: "get-activities",
    };


    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };

    modelObj: IObject = {};

    filterMenu = false; //filter foreign key conditional

    filterModel: IFilterModel = {}; //empty object for filter initialisation

    get model(): IModel {
        return ActivityLogModule.model;
    }

    get getModuleList(): IModuleNames[] {
        const list = JSON.parse(JSON.stringify(ActivityLogModule.moduleNames));
        list.unshift({ id: "0", name: "All (Default)" });

        return list;
    }

    get getUserList(): IUserLightResponse[] {
        const list = JSON.parse(JSON.stringify(UserModule.userList));
        list.unshift({ id: "0", name: "All (Default)"});
        return list;
    }

    /**
     * View Log
     * @param item
     */
    onView(item: IObject): void {
        this.modelObj = item;
        this.activityLogViewModal = true;
    }

    /**
     * Export data in CSV file
     */
    setExport(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element, index) => {
            rowIds[index] = element.id;
        });

        this.exportProps.ids = rowIds;
        (<any>this.$refs.exportbtn).exportToCSV();
    }

    /**
     * Filter
     */
    changeFilter(): void {
        let filter: IFilterModel = {};
        filter = this.filterByPeriod(filter);
        this.filterModel = filter;
        this.refresh();
        this.filterMenu = false;
    }

    /**
     * Reset Filter
     */
    resetFilter(): void {
        this.model.user_id = ["0"];
        this.model.module = ["0"];
        this.model.period_type = "10";
        this.model.start_date = format(new Date(), "yyyy-MM-dd");
        this.model.end_date = format(new Date(), "yyyy-MM-dd");
        this.changeFilter();
    }

    refreshData(): void {
        const self = this;
        setTimeout(() => {
            if (self.tab == "tab1") {
                self.refresh();
            }
        }, 100);
    }

    filterByPeriod(filter: IFilterModel): IFilterModel {
        if (this.model.period_type == "10") {
            filter.is_all_date = "1";
        } else {
            filter.is_all_date = "0";
        }
        if (filter.is_all_user == "1" || this.model.user_id[0] == "0") {
            delete filter.causer_id;
        } else {
            filter.causer_id = this.model.user_id.filter(
                (item, index) => item != null && item != undefined
            );
            delete filter.is_all_user;
        }
        if (filter.is_all_module == "1" || this.model.module[0] == "0") {
            delete filter.log_name;
        } else {
            filter.log_name = this.model.module.filter(
                (item, index) => item != null && item != undefined
            );
            delete filter.is_all_module;
        }

        if (this.model.is_all_date == "1" && this.model.period_type == "10") {
            delete filter.period_type;
            delete filter.created_at;
            delete filter.is_all_date;
        } else if (this.model.period_type == "0") {
            delete filter.is_all_date;
            filter.created_at = `${this.getFilterDateFormat(
                this.model.start_date
            )}to${this.customDayMonthDate(
                this.model.end_date,
                1,
                "days",
                true
            )}`;
        } else if (this.model.period_type == "1") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                3,
                "months",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.model.period_type == "2") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                6,
                "months",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.model.period_type == "3") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                1,
                "years",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (this.model.period_type == "4") {
            delete filter.is_all_date;
            filter.created_at = `${this.customDayMonthDate(
                new Date(),
                2,
                "years",
                false
            )}to${this.getFilterDateFormat(new Date())}`;
        } else if (
            this.model.period_type != "0" &&
            this.model.period_type != "10"
        ) {
            delete filter.is_all_date;
            delete filter.created_at;
        }
        return filter;
    }

    created(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CommonModule.getAll({
            apiName: "users",
            pagination: { page: 1, limit: 100 },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                UserModule.SET_USER_LIST(
                    response.data.data as IUserLightResponse[]
                );
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
}

export default ActivityLog;
