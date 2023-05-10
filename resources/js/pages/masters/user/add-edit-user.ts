import CommonServices from "../../../mixins/common";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { SnackbarModule } from "../../../store/snackbar";
import { Component, Mixins } from "vue-property-decorator";
import { UserModule } from "../../../store/user";
import CommonApis from "../../../mixins/common-apis";
import { HTMLClassModule } from "../../../store/htmlclass";

import { ResponseResult } from "../../../../assets/types/common";

import {
    IUserModel,
    IUserFullResponse,
    IUserValidations,
} from "../../../../assets/types/user";
import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";

import { RoleModule } from "@/store/role";
import { IRoleLightResponse } from "../../../../assets/types/role";

@Component({
    components: {
        ErrorBlockServer,
    },
})
class AddEditUser extends Mixins(CommonServices, CommonApis) {
    //Data Property
    errorMessage = "";
    password_rules =
        "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$";
    validationMessages: IUserValidations = {
        name: [
            {
                key: "required",
                value: "Name required",
            },
        ],

        email: [
            {
                key: "required",
                value: "Email required",
            },
            {
                key: "max",
                value: "Maximum length should be 191",
            },
            {
                key: "email",
                value: "Email is invalid",
            },
        ],
        contact_number: [
            {
                key: "required",
                value: "Mobile Number Required",
            },
            {
                key: "min",
                value: "Mobile Number should be of 10 digits",
            },
            {
                key: "max",
                value: "Mobile Number should be of 10 digits",
            },
        ],

        password: [
            {
                key: "required",
                value: "Password required",
            },
            {
                key: "min",
                value: "Password length should be at least 8 characters",
            },
            {
                key: "max",
                value: "Maximum length should be 15",
            },
            {
                key: "regex",
                value: "Password contains atleast One Uppercase, One Lowercase, One Number and One Special Character",
            },
        ],

        role_id: [
            {
                key: "required",
                value: "Role required",
            },
        ],
    };
    isSubmitting = false;

    isDataLoading = false;
    showPassword = false;
    checkEmailLoading = false;

    //Computed Property

    get model(): IUserModel {
        return UserModule.model;
    }

    get isEditMode(): boolean {
        return UserModule.editId ? <number>UserModule.editId > 0 : false;
    }

    get roleList(): IRoleLightResponse[] {
        return RoleModule.roleList;
    }

    //Methods

    /* JSON Form Submit - Start*/
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                // For Edit User
                if (UserModule.editId && <number>UserModule.editId > 0) {
                    apiName = "edit";
                    editId = UserModule.editId;
                }

                //To Submit JSON Request
                const sendParamModel = JSON.parse(JSON.stringify(self.model));
                UserModule[apiName]({ model: sendParamModel, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IUserFullResponse>
                        >
                    ) => {
                        this.onCancel();
                        SnackbarModule.setMsg(response.data.message as string);
                    },
                    (error) => {
                        self.isSubmitting = false;
                        self.errorMessage = self.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }
    /* JSON Form Submit - End*/

    /* Cancel */
    onCancel(): void {
        this.onModalClear("user", "CLEAR_STORE");
        this.$router.push({ name: "user-list" });
    }

    /**
     * When Single Master call is needed
     */
    commonDataFetch(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        this["$validator"].reset();
        this.isDataLoading = true;
        CommonModule.getAll({
            apiName: "roles",
            pagination: { page: 1 },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                RoleModule.SET_ROLE_LIST(
                    <IRoleLightResponse[]>response.data.data
                );
                this.isDataLoading = false;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.isDataLoading = false;
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    created(): void {
        this.$validator.extend("unique_email", {
            validate: (value) => {
                return new Promise((resolve) => {
                    let editId: string | number = "";
                    if (UserModule.editId && <number>UserModule.editId > 0) {
                        editId = UserModule.editId;
                    }
                    this.checkEmailLoading = true;
                    UserModule.checkEmail({
                        id: editId,
                        email: value,
                    }).then(
                        (response: AxiosResponse<ResponseResult<boolean>>) => {
                            resolve({
                                valid: <boolean>response.data.data,
                            });
                            this.checkEmailLoading = false;
                        }
                    );
                });
            },
        });

        this.resetStoreData("user").then(
            (response) => {
                const castedUserResponse = response as AxiosResponse<
                    ResponseResult<IUserFullResponse>
                >;
                if (castedUserResponse?.data?.data) {
                    const userModel: IUserModel = {
                        email: castedUserResponse.data?.data?.email,
                        password: castedUserResponse.data?.data?.password,
                        role_id: castedUserResponse.data?.data?.role_id,
                        name: castedUserResponse.data?.data?.name,
                        contact_number:
                            castedUserResponse.data?.data?.contact_number,
                    };

                    UserModule.SET_MODEL(userModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

        if (this.done24Hours("add-edit-user-call")) {
            this.commonDataFetch();
        }
    }
}

export default AddEditUser;
