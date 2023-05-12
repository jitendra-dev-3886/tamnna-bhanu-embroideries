import CommonServices from "../../mixins/common";
import ErrorBlockServer from "../../components/ErrorBlockServer.vue";
import Snackbar from "../../components/Snackbar.vue";
import { Component, Mixins } from "vue-property-decorator";
import { UserModule } from "@/store/user";
import { SnackbarModule } from "@/store/snackbar";
import { getTime } from "date-fns";
import { ILoginModel, ILoginValidations } from "../../../assets/types/auth";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../../assets/types/common";
import { ICurrentUserData } from "../../../assets/types/user";
import { PermissionModule } from "@/store/permission";
import { NavigationGuardNext,Route } from "vue-router";

@Component({
    components: {
        ErrorBlockServer,
        Snackbar,
    },
})
class Logoff extends Mixins(CommonServices) {
    errorMessage = "";
    // Validation Message
    validationMessages: ILoginValidations = {
        password: [
            {
                key: "required",
                value: "Password required"
            },
        ],
    };
    // login info
    loginDetail: ILoginModel = {
        email: <string>UserModule.currentUserData.email,
        contact_number:<string>UserModule.currentUserData.contact_number,
        password: "",
        g_recaptcha_response: "",
    };
    fpdialog = false;
    isSubmitting = false;
    showPassword = false;

    onSubmit(): void {
        if (this.loginDetail.email) {
        // set spinner to submit button

        this["$validator"].validate().then((valid) => {
            if (valid) {
                this.isSubmitting = true;
                UserModule.login(this.loginDetail).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICurrentUserData>
                            >
                    ) => {
                        this.isSubmitting = false;
                        this.errorMessage = "";
                        // Set Data of Current user in store
                        UserModule.SET_CURRENT_USER_DATA(
                            <ICurrentUserData>response.data.data
                        );
                        // go to which page after successfully login

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

                        localStorage.setItem(
                            "login-timestamp",
                            String(getTime(new Date()))
                        );
                        this["$router"].push(UserModule.defaultRouteUrl);
                        UserModule.RESET_DEFAULT_URL();
                    },
                    (error) => {
                        // If Login has Error
                        this.isSubmitting = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
     }
      else {
            this.$router.push("/login");
        }
    }

    /**
     //          * on google recaptcha execute
     //          * @param response - recaptcha token
     //          */
    onRecaptchaVerify(): void {
        if (
            process.env.MIX_GOOGLE_CAPTCHA_KEY &&
            process.env.MIX_MODE == "uat"
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

    get snackbar(): boolean {
        return SnackbarModule.snackbar;
    }

    get userFullName(): string {
        return <string>UserModule.userFullName;
    }

    get UserProfilePicture(): string {
        return <string>UserModule.userProfilePicture;
    }
   beforeRouteEnter(
        to: Route,
        from: Route,
        next: NavigationGuardNext<Vue>
    ): void {
        if (UserModule.currentUserData.contact_number== "") {
            next("/");
        }
        next();
    }
}

export default Logoff;
