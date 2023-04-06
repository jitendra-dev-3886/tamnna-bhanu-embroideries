import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule
} from "vuex-module-decorators";
import HTTP from "../common_services/api-services";
import {
    IPagination,
    ResponseResult,
    IDeleteProps
} from "../../assets/types/common";
import { isExistInLocalStorage, getSortType } from "@/filters/common";
import {
    IRoleModel,
    IParams,
    IRoleFullResponse,
    IRoleLightResponse
} from "../../assets/types/role";
import { AxiosResponse } from "axios";
// mutation types
function getEmptyState() {
    return {
        roleList: [],
        model: {
            name: ""
        },
        editId: 0
    };
}
export interface IRole {
    roleList: IRoleLightResponse[];
    model: IRoleModel;
    editId: IParams["editId"];
}

@Module({
    dynamic: true,
    store,
    name: "role",
    namespaced: true,
    preserveState: isExistInLocalStorage("role")
})
class Role extends VuexModule implements IRole {
    public roleList: IRoleLightResponse[] = getEmptyState().roleList;
    public model: IRoleModel = getEmptyState().model;
    public editId: IParams["editId"] = getEmptyState().editId;
    baseUrl = process.env.MIX_API_BASE_URL;

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_ROLE_LIST(payload: IRoleLightResponse[]) {
        this.roleList = payload;
    }

    @Mutation
    SET_MODEL(param: IRoleModel) {
        Object.keys(this.model).forEach(key => {
            if (param[key] == null) {
                param[key] = "";
            }
            this.model[key] = param[key];
        });
    }

    /**
     * Append role when receive channel event
     * @param payload
     */
    @Mutation
    APPEND_ROLE(payload: IRoleFullResponse) {
        this.roleList.push(payload);
    }

    /**
     * Update specific role when receive channel event
     * @param payload
     */
    @Mutation
    UPDATE_ROLE(payload: IRoleFullResponse) {
        const roleList = JSON.parse(JSON.stringify(this.roleList));
        const index = roleList.findIndex(x => x.id == payload.id);
        roleList[index] = payload;
        if (index != -1) {
            this.roleList = roleList;
        }
    }
    /**
     * Remove specific role when receive channel event
     * @param payload
     */
    @Mutation
    REMOVE_ROLE(payload: IRoleFullResponse) {
        const roleList = JSON.parse(JSON.stringify(this.roleList));
        const index = roleList.findIndex(x => x.id == payload.id);
        roleList.splice(index, 1);
        if (index != -1) {
            this.roleList = roleList;
        }
    }

    /**
     * Remove selected roles when receive channel event
     * @param payload
     */
    @Mutation
    REMOVE_SELECTED_ROLE(payload: string[]) {
        let roleList = JSON.parse(JSON.stringify(this.roleList));
        if (roleList.length > 0) {
            payload.forEach(id => {
                roleList = roleList.filter(x => x.id != id);
            });
            this.roleList = roleList;
        }
    }

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
    }

    /**
     * Used for add role
     * @param param
     */
    @Action({ rawError: true })
    add(
        param: IParams
    ): Promise<AxiosResponse<ResponseResult<IRoleFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}roles`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IRoleFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch(e => {
                    reject(e);
                });
        });
    }

    /**
     * Used for edit role
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: IParams
    ): Promise<AxiosResponse<ResponseResult<IRoleFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.put(`${this.baseUrl}roles/${param.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IRoleFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch(e => {
                    reject(e);
                });
        });
    }

    /**
     * Used for delete role
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}roles/${param}`)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch(e => {
                    reject(e);
                });
        });
    }

    /**
     * Used for multiple delete
     * @param param
     */
    @Action({ rawError: true })
    multiDelete(
        param: IDeleteProps
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}roles-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch(e => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular role record
     */
    @Action({ rawError: true })
    getById(): Promise<AxiosResponse<ResponseResult<IRoleFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}roles/${this.editId}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IRoleFullResponse>
                        >
                    ) => {
                        if (response.data?.data) {
                            const model = { name: response.data?.data?.name };
                            this.SET_MODEL(model);
                        }
                        resolve(response);
                    }
                )
                .catch(e => {
                    reject(e);
                });
        });
    }
}

export const RoleModule = getModule(Role);
