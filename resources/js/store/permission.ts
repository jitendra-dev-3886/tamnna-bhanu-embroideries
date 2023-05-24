import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import HTTP from "../common_services/api-services";
import { isExistInLocalStorage } from "@/filters/common";
import { IPermissionResponse } from "../../assets/types/permission";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../assets/types/common";

export interface IPermission {
    permissions: IPermissionResponse[];
    userPermissions: IPermissionResponse[];
    permissionDialog: boolean;
}

@Module({
    dynamic: true,
    store,
    name: "permission",
    namespaced: true,
    preserveState: isExistInLocalStorage("permission"),
})
class Permission extends VuexModule implements IPermission {
    public permissions: IPermissionResponse[] = [];
    public userPermissions: IPermissionResponse[] = [];
    permissionDialog = false;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_PERMISSION_DIALOGUE(payload: boolean) {
        this.permissionDialog = payload;
    }

    @Mutation
    SET_PERMISSIONS(param: IPermissionResponse[]) {
        this.permissions = param;
    }

    @Mutation
    CLEAR_PERMISSIONS() {
        this.permissions = [];
    }

    @Mutation
    SET_USER_PERMISSIONS(param: IPermissionResponse[]) {
        this.userPermissions = param;
    }

    /** Update specific user permission when receive channel event
     * @param payload
     */
    @Mutation
    UPDATE_PERMISSIONS(payload: IPermissionResponse) {
        const permissionList = JSON.parse(JSON.stringify(this.userPermissions));
        const index = permissionList.findIndex((x) => x.id == payload[0].id);
        permissionList[index] = payload[0];
        if (index != -1) {
            this.userPermissions = permissionList;
            this.permissions = permissionList;
        }
    }

    /**
     * Used for set/unset permission
     * @param param
     */
    @Action
    edit(param): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}set-unset-permission-to-role`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get role by permission
     * @param param
     */
    @Action
    getById({
        roleId,
        isUpdateUserPermission,
    }: {
        roleId: string;
        isUpdateUserPermission: boolean;
    }): Promise<AxiosResponse<ResponseResult<IPermissionResponse[]>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}get-role-by-permissions/${roleId}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IPermissionResponse[]>
                        >
                    ) => {
                        this.SET_PERMISSIONS(
                            response.data.data as IPermissionResponse[]
                        );
                        if (isUpdateUserPermission) {
                            this.SET_USER_PERMISSIONS(
                                response.data.data as IPermissionResponse[]
                            );
                        }
                        /*this.SET_USER_PERMISSIONS(
                            response.data.data as IPermissionResponse[]
                        );*/
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }
}

export const PermissionModule = getModule(Permission);
