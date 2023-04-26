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

    get allData() {
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
            this.$getConst("DASHBOARD"),
            "getTicketStatusCount"
        );
    }

   redirectTo(path: number): void {
        if (path == 1) {
            this.$router.push("/masters/customer");
        } else if(path==2){
            this.$router.push(`/masters/category`);
        }
        else if(path==3){
            this.$router.push(`/masters/product`);
        }
        else{
            this.$router.push(`/masters/order`);
        }

    }

    created(): void {
        if (this.canTicketCounterAccessible) {
            CommonModule.getAll({
                apiName: "dashboards",
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
