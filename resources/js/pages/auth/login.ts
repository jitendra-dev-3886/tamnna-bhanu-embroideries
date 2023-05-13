import CommonServices from "../../mixins/common";
import ErrorBlockServer from "../../components/ErrorBlockServer.vue";
import ForgotPasswordModal from "../../components/auth/ForgotPasswordModal.vue";
import Snackbar from "../../components/Snackbar.vue";
import PermissionDialog from "../../components/PermissionDialog.vue";
import Component, { mixins } from "vue-class-component";
import { PermissionModule } from "../../store/permission";
import { SnackbarModule } from "../../store/snackbar";
import { UserModule } from "../../store/user";
import { getTime } from "date-fns";
import { ILoginValidations, ILoginModel } from "../../../assets/types/auth";
import { AxiosResponse } from "axios";
import {
    ResponseResult,
    ILoginConfirmationProps,
} from "../../../assets/types/common";
import { ICurrentUserData } from "../../../assets/types/user";
import { NavigationGuardNext, Route } from "vue-router";
Component.registerHooks(["beforeRouteEnter"]);

@Component({
    components: {
        ErrorBlockServer,
        ForgotPasswordModal,
        Snackbar,
        PermissionDialog,
    },
})
class Login extends mixins(CommonServices) {
    // Validation Message
    showPassword = false;
    validationMessages: ILoginValidations = {
        email: [
            { key: "required", value: "Email required" },
            {
                key: "email",
                value: "Email is invalid",
            },
        ],
        contact_number: [
            { key: "required", value: "Contact Number/Email-id required" },
            {
                key: "contact_number",
                value: "Contact Number/Email-id is invalid",
            },
        ],
        password: [
            {
                key: "required",
                value: "Password required",
            },
        ],
    };
    // login info
    loginDetail: ILoginModel = {
        email: "",
        contact_number: "",
        password: "",
        g_recaptcha_response: "",
    };
    remember_me = "";
    forgotPasswordDialog = false;
    isSubmitting = false;
    errorMessage = "";
    loginConfirmationModal = false;
    // isBatchRequestLoading: boolean;

    get permissionDialog(): boolean {
        return PermissionModule.permissionDialog;
    }

    get snackbar(): boolean {
        return SnackbarModule.snackbar;
    }

    get defaultRouteUrl(): string {
        return UserModule.defaultRouteUrl;
    }

    loginConfirmation: ILoginConfirmationProps = {
        title: "Confirmation !",
        description: this.$getConst("LOGOUT_LAST_DEVICE"),
        btnCancelText: this.$getConst("BTN_CANCEL"),
        btnConfirmationText: this.$getConst("BTN_OK"),
        isDisabledCancelButton: false,
        isDisabledAllButtons: false,
    };
    login(isUserLogout: string): void {
        const sendData = JSON.parse(JSON.stringify(this.loginDetail));
        sendData.is_logout = isUserLogout;
        this.isSubmitting = true;
        UserModule.login(sendData)
            .then(
                (response: AxiosResponse<ResponseResult<ICurrentUserData>>) => {
                    this.errorMessage = "";
                    // this.CommonGetSettingList();
                    // Set Data of Current user in store
                    UserModule.SET_CURRENT_USER_DATA(
                        <ICurrentUserData>response.data.data
                    );
                    // Set permission data
                    if (response.data?.data?.permissions) {
                        const permission = <[]>response.data.data.permissions;
                        if (permission.length > 0) {
                            PermissionModule.SET_USER_PERMISSIONS(
                                response.data.data.permissions
                            );
                        }
                    }
                    // go to which page after successfully login
                    localStorage.setItem(
                        "login-timestamp",
                        String(getTime(new Date()))
                    );
                    if (
                        (response.data.data as any).redirect_url &&
                        (response.data.data as any).redirect_url != ""
                    ) {
                        let route_arr = (this.$router.options as any).routes[1]
                            .children;
                        let arr1 = route_arr[route_arr.length - 1];
                        let arr2 = route_arr.concat(arr1.children);
                        arr2.forEach((element) => {
                            if (element.meta) {
                                if (
                                    element.meta.redirectPermission &&
                                    element.meta.redirectPermission != ""
                                ) {
                                    if (
                                        (response.data.data as any)
                                            .redirect_url ==
                                        element.meta.redirectPermission
                                    ) {
                                        this["$router"].push(
                                            element.meta.redirectUrl
                                        );
                                    }
                                }
                            } else {
                                element.children.forEach((element) => {
                                    if (element.meta) {
                                        if (
                                            element.meta.redirectPermission &&
                                            element.meta.redirectPermission !=
                                                ""
                                        ) {
                                            if (
                                                (response.data.data as any)
                                                    .redirect_url ==
                                                element.meta.redirectPermission
                                            ) {
                                                this["$router"].push(
                                                    element.meta.redirectUrl
                                                );
                                            }
                                        }
                                    }
                                });
                            }
                        });
                        this.isSubmitting = false;
                    } else {
                        this.isSubmitting = true;
                        this["$router"].push("/dashboard");
                    }
                    // this["$router"].push(this.defaultRouteUrl);
                    // this["$router"].push("/dashboard");
                    UserModule.RESET_DEFAULT_URL();
                }
            )
            .catch((error) => {
                this.isSubmitting = false;
                this.errorMessage = this.getAPIErrorMessage(error.response);
            });
    }

    /**
     * Login Submit Method
     */
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                this.isSubmitting = true;

                //const sendData = JSON.parse(JSON.stringify(this.loginDetail));
                UserModule.checkUserLoggedInOrNot({
                    email: this.loginDetail.email,
                    password: this.loginDetail.password,
                })
                    .then((response: AxiosResponse<ResponseResult<string>>) => {
                        UserModule.SET_REMEMBER_ME(this.remember_me);
                        this.login("1");
                    })
                    .catch((error) => {
                        this.isSubmitting = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    });
            }
        });
    }

    isLogin(isLogout: boolean): void {
        if (isLogout) {
            this.login("1");
        }
        this.loginConfirmationModal = false;
    }

    rememberMe(): void {
        UserModule.SET_REMEMBER_ME(this.remember_me);
    }
    /**
     * on google recaptcha execute
     */
    onRecaptchaVerify(): void {
        // if (
        //     (process.env.MIX_GOOGLE_CAPTCHA_KEY &&
        //         process.env.MIX_MODE == "production") ||
        //     process.env.MIX_MODE == "uat"
        // ) {
        //     this.$validator.validate().then((valid) => {
        //         if (valid) {
        //             this["$recaptcha"]("login").then(
        //                 (token: string) => {
        //                     console.log("token", token);
        //                     this.loginDetail.g_recaptcha_response = token
        //                         ? token
        //                         : "";
        //                     this.onSubmit();
        //                 },
        //                 (error) => {
        //                     this.loginDetail.g_recaptcha_response = "";
        //                 }
        //             );
        //         }
        //     });
        // } else {
        //     this.onSubmit();
        // }

        this.onSubmit();
    }

    created(): void {
        // if (
        //     (process.env.MIX_GOOGLE_CAPTCHA_KEY &&
        //         process.env.MIX_MODE == "production") ||
        //     process.env.MIX_MODE == "uat"
        // ) {
        //     this["$recaptchaLoaded"]().then(() => {
        //         this["$recaptchaInstance"].showBadge();
        //     });
        // }
    }

    beforeDestroy(): void {
        // if (
        //     (process.env.MIX_GOOGLE_CAPTCHA_KEY &&
        //         process.env.MIX_MODE == "production") ||
        //     process.env.MIX_MODE == "uat"
        // ) {
        //     this["$recaptchaInstance"].hideBadge();
        // }
    }

    /**
     * Abort login route if user already logged in
     * @param to
     * @param from
     * @param next
     */

    beforeRouteEnter(
        to: Route,
        from: Route,
        next: NavigationGuardNext<Vue>
    ): void {
        next(() => {
            // TODO: do not need this here any more as have redirected in index file only
            var authorization = "";
            if (UserModule.remember_me != "1") {
                authorization = "";
            } else {
                authorization = UserModule.currentUserData.authorization;
            }
            if (authorization && authorization != "" && authorization != null) {
                if (from.fullPath == "/") {
                    next(UserModule.defaultRouteUrl);
                } else {
                    next(from.fullPath);
                }
            } else {
                next();
            }
        });
    }
}

export default Login;
