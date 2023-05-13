import HTTP from "../common_services/api-services";
import store from "./store";
import {
    VuexModule,
    Module,
    Action,
    Mutation,
    getModule,
} from "vuex-module-decorators";
import {
    IImportReqParams,
    IImportCSVLogResponse,
    IImportResponse,
    ResponseResult,
    IDeleteProps,
    IPagination,
} from "../../assets/types/common";
import { isExistInLocalStorage } from "@/filters/common";
import {
    ICurrentUserData,
    IUserModel,
    IUserParams,
    IUserFullResponse,
    IUserCheckEmail,
    IUserLightResponse,
} from "../../assets/types/user";
import { AxiosResponse } from "axios";
import { ILoginModel,ICheckLoginModel } from "../../assets/types/auth";

function getEmptyState() {
    return {
        model: {
            email: "",
            role_id: "",
            password: "",
            name: "",
            contact_number: "",
        },
        pagination: {
            query: "",
            page: 1,
            limit: 10,
            orderBy: "",
            descending: "default",
            filter: "",
        },
        editId: 0,
        remember_me: "0",
        tableData: {
            data: [],
        },
        userList: [],
        currentUserData: {
            id: "",
            email: "",
            name: "",
            contact_number: "",
            company_name: "",
            password: "",
            city: "",
            email_verified_at: "",
            role_id: "",
            role: {
                guard_name: "",
                id: "",
                landing_page: "",
                name: "",
            },
            profile: "",
            permissions: [],
            authorization: "",
            refresh_token: " ",
            sample_excels: [
                {
                    sample_user: "",
                    sample_category: "",
                    sample_product: "",
                    sample_order: "",
                    sample_cart: "",
                },
            ],
            user_status: "",
        },
        viewModel: {
            id: "",
            email: "",
            name: " ",
            contact_number: " ",
            company_name: "",
            password: "",
            city: "",
            email_verified_at: "",
            role_id: "",
            user_status: "",
            role: {
                guard_name: "",
                id: "",
                landing_page: "",
                name: "",
            },
        },
        defaultRouteUrl: "/dashboard",
    };
}

export interface IUser {
    pagination: IPagination;
    tableData: ResponseResult<IUserFullResponse[]>;
    userList: IUserLightResponse[];
    currentUserData: ICurrentUserData;
    model: IUserModel;
    viewModel: IUserFullResponse;
    editId: IUserParams["editId"];
    remember_me: IUserParams["remember_me"];
    defaultRouteUrl: string;
}

@Module({
    dynamic: true,
    store,
    name: "user",
    namespaced: true,
    preserveState: isExistInLocalStorage("user"),
})
class User extends VuexModule implements IUser {
    public pagination: IPagination = getEmptyState().pagination;
    public tableData: ResponseResult<IUserFullResponse[]> =
        getEmptyState().tableData;
    public userList: IUserLightResponse[] = getEmptyState().userList;
    public currentUserData: ICurrentUserData = getEmptyState().currentUserData;
    public model: IUserModel = getEmptyState().model;
    public viewModel: IUserFullResponse = getEmptyState().viewModel;
    public editId: IUserParams["editId"] = getEmptyState().editId;
    public remember_me: IUserParams["remember_me"] =
        getEmptyState().remember_me;
    public defaultRouteUrl: string = getEmptyState().defaultRouteUrl;
    public baseUrl = process.env.MIX_API_BASE_URL;
    public isCurrentPageRetain = false;

    @Mutation
    public REMOVE_AUTHORIZATION(authorization: string) {
        this.currentUserData.authorization = authorization;
    }

    @Mutation
    public CLEAR_USER_DATA() {
        this.currentUserData = getEmptyState().currentUserData;
    }

    @Mutation
    public SET_CURRENT_USER_DATA(currentUserData: ICurrentUserData) {
        this.currentUserData = currentUserData;
    }

    @Mutation
    SET_EDIT_ID(payload: string | number) {
        this.editId = payload;
    }

    @Mutation
    SET_REMEMBER_ME(payload: string | number) {
        this.remember_me = payload;
    }
    @Mutation
    SET_MODEL(param: IUserModel) {
        this.model = param;
    }

    @Mutation
    SET_VIEW_MODEL(param: IUserFullResponse) {
        this.viewModel = param;
    }

    @Mutation
    SET_USER_LIST(payload: IUserLightResponse[]) {
        this.userList = payload;
    }

    @Mutation
    CLEAR_STORE() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }

    @Mutation
    CLEAR_MODEL() {
        this.model = getEmptyState().model;
        this.editId = getEmptyState().editId;
        this.viewModel = getEmptyState().viewModel;
    }

    @Mutation
    public SET_TOKENS({
        access_token,
        refresh_token,
    }: {
        access_token: string;
        refresh_token: string;
    }) {
        this.currentUserData.refresh_token = refresh_token;
        this.currentUserData.authorization = access_token;
    }

    @Mutation
    SET_DEFAULT_URL(payload: string) {
        this.defaultRouteUrl = payload;
    }

    @Mutation
    RESET_DEFAULT_URL() {
        this.defaultRouteUrl = getEmptyState().defaultRouteUrl;
    }

    @Action({ rawError: true }) //This is for if request reject
    checkEmail(
        param: IUserCheckEmail
    ): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}check-email`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    @Action({ rawError: true }) //This is for if request reject
    public login(
        userInfo: ILoginModel
    ): Promise<AxiosResponse<ResponseResult<ICurrentUserData>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}login`, userInfo)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICurrentUserData>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    @Action({ rawError: true }) //This is for if request reject
    public checkUserLoggedInOrNot(
        userInfo: ICheckLoginModel
    ): Promise<AxiosResponse<ResponseResult<string>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}check-user-logged-in-or-not`, userInfo)
                .then((response: AxiosResponse<ResponseResult<string>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    @Action({ rawError: true })
    userLogout(): Promise<AxiosResponse<ResponseResult<string>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}logout`)
                .then((response: AxiosResponse<ResponseResult<string>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    @Action({ rawError: true })
    logoff(param: string): void {
        this.REMOVE_AUTHORIZATION(param);
    }

    /**
     * Used for registration
     * @param param
     */
    @Action({ rawError: true })
    register(
        param: IUserParams
    ): Promise<AxiosResponse<ResponseResult<IUserFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}register`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IUserFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for user create ()
     * @param param
     */
    @Action({ rawError: true })
    create(
        param: IUserParams
    ): Promise<AxiosResponse<ResponseResult<IUserFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}users`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IUserFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get a particular user record
     */
    @Action({ rawError: true })
    getById(
        id: string | number
    ): Promise<AxiosResponse<ResponseResult<IUserFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.get(`${this.baseUrl}users/${id}`)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IUserFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for edit user
     * @param param
     */
    @Action({ rawError: true })
    edit(
        param: IUserParams
    ): Promise<AxiosResponse<ResponseResult<IUserFullResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.put(`${this.baseUrl}users/${this.editId}`, param.model)
                .then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IUserFullResponse>
                        >
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for delete user
     * @param param
     */
    @Action({ rawError: true })
    delete(param: string): Promise<AxiosResponse<ResponseResult<boolean>>> {
        return new Promise((resolve, reject) => {
            HTTP.delete(`${this.baseUrl}users/${param}`)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
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
            HTTP.post(`${this.baseUrl}users-delete-multiple`, param)
                .then((response: AxiosResponse<ResponseResult<boolean>>) => {
                    resolve(response);
                })
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used for import functionality (upload file)
     * @param param
     */
    @Action({ rawError: true })
    import(
        param: IImportReqParams
    ): Promise<AxiosResponse<ResponseResult<IImportResponse>>> {
        return new Promise((resolve, reject) => {
            HTTP.post(`${this.baseUrl}users-import-bulk`, param)
                .then(
                    (
                        response: AxiosResponse<ResponseResult<IImportResponse>>
                    ) => {
                        resolve(response);
                    }
                )
                .catch((e) => {
                    reject(e);
                });
        });
    }

    /**
     * Used to get user fullname
     */
    get userFullName() {
        return this.currentUserData ? "Adminstrator" : "Sub Admin";
    }

    /**
     * Used to get user profile image
     */
    get userProfilePicture() {
        if (
            this.currentUserData.profile &&
            this.currentUserData.profile == ""
        ) {
            return "/images/profile.png";
        }
        return this.currentUserData.profile;
    }
}

export const UserModule = getModule(User);
