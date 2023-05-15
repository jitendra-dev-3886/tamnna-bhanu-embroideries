import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import CommonServices from "../../../mixins/common";
import { HTMLClassModule } from "../../../store/htmlclass";
import { SnackbarModule } from "../../../store/snackbar";
import Component, { mixins } from "vue-class-component";
import { PermissionModule } from "../../../store/permission";
import { RoleModule } from "../../../store/role";
import { CommonModule } from "../../../store/common";
import { UserModule } from "../../../store/user";
import { IRoleLightResponse } from "../../../../assets/types/role";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../../../assets/types/common";
import {
    IModel,
    IPermissionObject,
    IPermissionResponse
} from "../../../../assets/types/permission";
import { ICurrentUserData } from "../../../../assets/types/user";

@Component({
    components: {
        ErrorBlockServer
    }
})
class Permission extends mixins(CommonServices) {
    role_id = "";

    get permissions(): IPermissionResponse[] {
        return PermissionModule.permissions;
    }

    get roleList(): IRoleLightResponse[] {
        return RoleModule.roleList;
    }

    get currentUser(): ICurrentUserData {
        return UserModule.currentUserData;
    }

    mounted(): void {
        PermissionModule.CLEAR_PERMISSIONS();
        HTMLClassModule.addBodyClassName("page-loading");
        CommonModule.getAll({ apiName: "roles", pagination: { isLight: true } }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                RoleModule.SET_ROLE_LIST(
                    response.data.data as IRoleLightResponse[]
                );
            },
            error => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    getPermissions(): void {
        PermissionModule.getById(this.role_id)
            .then()
            .catch(error => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            });
    }

    /**
     * reset permission if permission is set or revoked from current role
     */
    resetPermission(): void {
        PermissionModule.getById(this.role_id)
            .then()
            .catch(error => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            });
    }

    editPermission(permission: IPermissionObject): void {
        const sendParams: IModel = {
            role_id: this.role_id,
            permission_id: permission.id,
            is_permission: permission.is_permission
        };
        HTMLClassModule.addBodyClassName("page-loading");
        PermissionModule.edit(sendParams).then(
            (response: AxiosResponse<ResponseResult<boolean>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                if (this.currentUser.role_id == this.role_id) {
                    this.resetPermission();
                }
                SnackbarModule.setMsg(response.data.message as string);
            },
            error => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
}

export default Permission;
