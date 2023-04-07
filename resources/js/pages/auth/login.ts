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
import { ResponseResult } from "../../../assets/types/common";
import { ICurrentUserData } from "../../../assets/types/user";

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
    validationMessages: ILoginValidations = {
        email: [
            { key: "required", value: "Email required" },
            {
                key: "email",
                value: "Email is invalid",
            },
        ],
        password: [
            { key: "required", value: "Password required" },
            {
                key: "min",
                value: "Password length should be at least 6",
            },
        ],
    };
    // login info
    loginDetail: ILoginModel = {
        email:"",
        contact_number: "",
        password: "",
        g_recaptcha_response: "",
    };
    forgotPasswordDialog = false;
    isSubmitting = false;
    errorMessage = "";

    get permissionDialog(): boolean {
        return PermissionModule.permissionDialog;
    }

    get snackbar(): boolean {
        return SnackbarModule.snackbar;
    }

    get defaultRouteUrl(): string {
        return UserModule.defaultRouteUrl;
    }

    /**
     * Login Submit Method
     */
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                this.isSubmitting = true;
                const sendData = JSON.parse(JSON.stringify(this.loginDetail));
                UserModule.login(sendData)
                    .then(
                        (
                            response: AxiosResponse<
                                ResponseResult<ICurrentUserData>
                                >
                        ) => {
                            this.isSubmitting = false;
                            // Set Data of Current user in store
                            UserModule.SET_CURRENT_USER_DATA(
                                <ICurrentUserData>response.data.data
                            );
                            // Set permission data
                            if (response.data?.data?.permissions) {
                                const permission = <[]>(
                                    response.data.data.permissions
                                );
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
                            this.subscribePrivateChannel();
                            this["$router"].push(this.defaultRouteUrl);
                            UserModule.RESET_DEFAULT_URL();
                        }
                    )
                    .catch((e) => {
                        this.isSubmitting = false;
                        this.errorMessage = this.getAPIErrorMessage(e.response);
                    });
            }
        });
    }

    /**
     * on google recaptcha execute
     */
    onRecaptchaVerify(): void {
        if (
            process.env.MIX_GOOGLE_CAPTCHA_KEY &&
            process.env.MIX_MODE == "production"
        ) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this["$recaptcha"]("login").then(
                        (token: string) => {
                            this.loginDetail.g_recaptcha_response = token;
                            this.onSubmit();
                        },
                        (error) => {
                            this.loginDetail.g_recaptcha_response = "";
                        }
                    );
                }
            });
        } else {
            this.onSubmit();
        }
    }

    created(): void {
        if (
            process.env.MIX_GOOGLE_CAPTCHA_KEY &&
            process.env.MIX_MODE == "production"
        ) {
            this["$recaptchaLoaded"]().then(() => {
                this["$recaptchaInstance"].showBadge();
            });
        }
    }

    beforeDestroy(): void {
        if (
            process.env.MIX_GOOGLE_CAPTCHA_KEY &&
            process.env.MIX_MODE == "production"
        ) {
            this["$recaptchaInstance"].hideBadge();
        }
    }
}

export default Login;
