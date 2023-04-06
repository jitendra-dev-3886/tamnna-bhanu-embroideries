import { Component, Prop, Mixins } from "vue-property-decorator";
import ErrorBlockServer from "../../components/ErrorBlockServer.vue";
import { SnackbarModule } from "@/store/snackbar";
import { ChangePasswordModule } from "@/store/change-password";
import CommonServices from "../../mixins/common";
import {
    IChangePasswordValidations,
    IChangePasswordModel,
} from "../../../assets/types/auth";

@Component({
    components: {
        ErrorBlockServer,
    },
})
class ChangePassword extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    errorMessage = "";

    isSubmitting = false;

    show_old_password = false;

    show_new_password = false;

    show_new_confirmation_password = false;

    validationMessages: IChangePasswordValidations = {
        old_password: [{ key: "required", value: "Current password required" }],
        new_password: [
            { key: "required", value: "New password required" },
            {
                key: "min",
                value: "Password length should be at least 6",
            },
        ],
        confirm_password: [
            { key: "required", value: "New password confirmation required" },
            {
                key: "min",
                value: "Password length should be at least 6",
            },
            {
                key: "confirmed",
                value: "New password confirmation does not match",
            },
        ],
    };

    get model(): IChangePasswordModel {
        return ChangePasswordModule.model;
    }

    onSubmit(): void {
        this["$validator"].validate().then((valid) => {
            if (valid) {
                this.isSubmitting = true;
                ChangePasswordModule.changePassword(this.model).then(
                    () => {
                        this.isSubmitting = false;
                        SnackbarModule.setMsg(
                            this.$getConst("CHANGED_PASSWORD")
                        );
                        this.onCancel();
                    },
                    (error) => {
                        this.isSubmitting = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }

    /**
     * Cancel Method
     */
    onCancel(): void {
        this.show_old_password = false;
        this.show_new_password = false;
        this.show_new_confirmation_password = false;
        this.$validator.reset();
        this.isSubmitting = false;
        this.errorMessage = "";
        this.$emit("input");
        this.onModalClear("changePassword");
    }
}

export default ChangePassword;
