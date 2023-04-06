import CommonServices from "@/mixins/common";
import ErrorBlockServer from "@/components/ErrorBlockServer.vue";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { RoleModule } from "@/store/role";
import { SnackbarModule } from "@/store/snackbar";
import {
    IRoleModel,
    IRoleFullResponse,
    IValidations,
} from "../../../../assets/types/role";
import { AxiosResponse } from "axios";
import { ResponseResult } from "../../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
    },
})
class AddRole extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    errorMessage = "";
    validationMessages: IValidations = {
        role: [
            { key: "required", value: "Role required" },
            { key: "max", value: "Maximum length should be 191" },
        ],
    };
    loading = false;

    get model(): IRoleModel {
        return RoleModule.model;
    }

    get isEditMode(): boolean {
        return RoleModule.editId ? RoleModule.editId > 0 : false;
    }

    /*
     * Submit action of form */
    addAction(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                // loader enable
                this.loading = true;
                // apiName is method to call the from the store
                let apiName = "add";
                let editId: string | number = "";
                if (RoleModule.editId && RoleModule.editId > 0) {
                    apiName = "edit";
                    editId = RoleModule.editId;
                }
                const sendData = {
                    name: this.model.name,
                };
                RoleModule[apiName]({ model: sendData, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IRoleFullResponse>
                        >
                    ) => {
                        SnackbarModule.setMsg(response.data.message as string);
                        this.onCancel();
                        this.$emit("refresh-table");
                        this.loading = false;
                    },
                    (error) => {
                        this.loading = false;
                        this.errorMessage = this.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }
    onCancel(): void {
        // clear model
        this.onModalClear("role", "CLEAR_STORE");
    }
}

export default AddRole;
