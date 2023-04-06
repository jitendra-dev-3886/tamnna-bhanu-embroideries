import CommonServices from "../../mixins/common";
import ErrorBlockServer from "../../components/ErrorBlockServer.vue";
import { SnackbarModule } from "@/store/snackbar";
import Component, { mixins } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import { ForgotPasswordModule } from "@/store/forgot-password";
import { IForgotPasswordValidations } from "../../../assets/types/auth";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
    },
})
export default class ForgotPassword extends mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    email = "";
    errorMessage = "";
    validationMessages: IForgotPasswordValidations = {
        email: [
            {
                key: "required",
                value: "Email required",
            },
            { key: "email", value: "Email is invalid" },
        ],
    };
    isSubmittingForgotPassword = false;
    /**
     * Cancel Method
     */
    onCancel(): void {
        this.email = "";
        this.isSubmittingForgotPassword = false;
        this.$validator.reset();
        this.isSubmitting = false;
        this.errorMessage = "";
        this.$emit("input");
    }

    /**
     * Submit of Forgot Password Modal
     */
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                this.isSubmittingForgotPassword = true;

                ForgotPasswordModule.sendForgotPasswordEmail({
                    email: this.email,
                }).then(
                    (response: AxiosResponse<ResponseResult<string>>) => {
                        SnackbarModule.setMsg(<string>response.data.data);
                        this.onCancel();
                    },
                    (error) => {
                        this.isSubmittingForgotPassword = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }
}
