import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";
import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../mixins/common";
import { IDashboard, ResponseResult } from "../../../assets/types/common";
import { isPermission } from "@/filters/permission-filters";
import { PermissionModule } from "@/store/permission";

@Component({
    name: "Dashboard",
})
class Dashboard extends Mixins(CommonServices) {
    dashboardData: IDashboard = {
        customers: "0",
        categories: "0",
        products: "0",
        orders: "0",
        completed: "0",
    };

    get allTickets() {
        return (
            Number(this.dashboardData.customers) +
            Number(this.dashboardData.categories) +
            Number(this.dashboardData.products)+
            Number(this.dashboardData.orders)
        );
    }

    get canTicketCounterAccessible(): boolean {
        return isPermission(
            PermissionModule.userPermissions,
            this.$getConst("TICKETS"),
            "getTicketStatusCount"
        );
    }

    viewTicktLists(statusType: string): void {
        if (statusType == "-1") {
            this.$router.push("/masters/ticket");
        } else {
            this.$router.push(`/masters/ticket/${statusType}`);
        }
    }

    created(): void {
        if (this.canTicketCounterAccessible) {
            CommonModule.getAll({
                apiName: "get-ticket-status-count",
                pagination: {},
            }).then(
                (response: AxiosResponse<ResponseResult<unknown>>) => {
                    this.dashboardData = response.data.data as IDashboard;
                },
                (error) => {
                    this.showDialog(this.getAPIErrorMessage(error.response));
                }
            );
        }
    }
}
export default Dashboard;
