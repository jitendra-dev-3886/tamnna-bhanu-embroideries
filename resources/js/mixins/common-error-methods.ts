import Vue from "vue";
import Component from "vue-class-component";
import { UserModule } from "../store/user";
import { ErrorModule } from "../store/error";
import { IObject } from "../../assets/types/common";
import { ErrorBag } from "vee-validate";
import { AxiosResponse } from "axios";

@Component
class CommonErrorMethods extends Vue {
    /**
     * Used error rule check
     * @param field - name of the field
     * @param errorArr
     */
    getErrorRule(field: string, errorArr: ErrorBag): string | undefined {
        const error = errorArr.items.find((item) => {
            if (item.scope) {
                return `${item.scope}.${item.field}` == field;
            }
            return item.field == field;
        });
        if (error) {
            return error.rule;
        }
        return "";
    }

    /**
     * Used for displaying error message
     * @param field - name of the field
     * @param errorArr
     * @param validationArray
     * @returns Message
     */
    getErrorValue(
        field: string,
        errorArr: ErrorBag,
        validationArray: any
    ): string {
        let rule: string | undefined = "";

        rule = this.getErrorRule(field, errorArr);

        if (rule) {
            const arr = field.split("."); // with scopes
            if (arr.length == 1) {
                // eslint-disable-next-line prefer-destructuring
                field = arr[0];
            } else {
                // eslint-disable-next-line prefer-destructuring
                field = arr[1];
            }
            const index = validationArray[field].findIndex(
                (p) => p.key == rule
            );
            return validationArray[field][index].value;
        }
        return "";
    }

    /**
     * Used for changing
     * :error property where only color needs to change and no message needs to be shown
     * e.g; checkbox
     * @param field - name of the field
     * @param errorArr
     * @returns {boolean}
     */
    getErrorCount(field: string, errorArr: ErrorBag): boolean {
        let rule: string | undefined = "";
        rule = this.getErrorRule(field, errorArr);
        return !!rule;
    }

    /**
     * Used for get API error messsage
     * @param response - response of error
     * @returns error
     */
    getAPIErrorMessage(response: AxiosResponse): string {
        let error = "Something went wrong. Please try again later.";
        if (!response) return error;
        if (response.status == 422) {
            error =
                response.data && response.data.errors
                    ? response.data.errors
                    : error;
            let errorString = "";
            // eslint-disable-next-line no-restricted-syntax,guard-for-in
            // for (const errorItem of error) {
            //     errorString += `${errorItem}<br>`;
            // }
            for (const key of Object.keys(error)) {
                errorString += `${error[key]}<br>`;
            }

            error = errorString;
            if (response.data.error) {
                if (response.data.error.errors) {
                    error = this.getErrosFromResponse(
                        response.data.error.errors
                    );
                } else {
                    error = response.data.error;
                }
            }
        } else if (response.status == 404) {
            if (response.data.error) {
                if (response.data.error.errors) {
                    error = this.getErrosFromResponse(
                        response.data.error.errors
                    );
                } else {
                    error = response.data.error;
                }
            }
        } else if (response.status == 403) {
            error = response.data;
        } else if (response.status == 427) {
            error = response.data.message;
        } else if (response.status == 401) {
            this.logout();
        }
        return error;
    }

    /**
     * Used for get error code
     * @param response - response of error
     * @returns error
     */
    getErrorCode(response: AxiosResponse): string | number {
        let error = "Something went wrong. Please try again later.";
        if (response.status == 422) {
            error =
                response.data && response.data.errors
                    ? response.data.errors
                    : error;
            let errorString = "";
            // eslint-disable-next-line no-restricted-syntax,guard-for-in
            for (const key of Object.keys(error)) {
                errorString += `${error[key]}<br>`;
            }
            error = errorString;
            if (response.data.error) {
                error = response.data.error;
            }
        } else if (response.status == 403) {
            error = response.data;
        } else if (response.status == 427) {
            error = response.data.message;
        } else if (response.status == 401) {
            this.logout();
        }
        return error;
    }

    /**
     * Used for get errors from response
     * @param response - response of error
     * @returns error
     */
    getErrosFromResponse(response: IObject[]): string {
        let err = "";
        Object.keys(response).forEach((key) => {
            response[key].forEach((item) => {
                err = `${err + item}<br/>`;
            });
        });
        return err;
    }

    /**
     * Used for get modal API error message
     * @param response - response of error
     * @returns error
     */
    getModalAPIerrorMessage(response: AxiosResponse): IObject[] {
        const err: IObject[] = [];
        const self = this;
        Object.keys(response).forEach((key) => {
            const val = response[key];
            if (!Object.prototype.hasOwnProperty.call(val, "data")) {
                err.push({
                    name: key,
                    message: self.getErrorCode(response[key]),
                });
            }
        });
        return err;
    }

    /**
     * Common method to display error dialogue
     * @param errorArr
     */
    showDialog(errorArr: string | []): void {
        ErrorModule.setErrors(errorArr);
        ErrorModule.willDisplayDialog(true);
    }

    /**
     *  Logout
     */
    logout(): void {
        UserModule.userLogout().then(
            () => {
                localStorage.clear();
                UserModule.SET_DEFAULT_URL(window.location.pathname);
                UserModule.CLEAR_USER_DATA();
                this["$router"].push("/");
            },
            (error) => {
                this.showDialog(error.response);
            }
        );
    }

    // Get Common Error Props for Custom Component
    getErrorProps(
        name: string,
        errorType: string,
        validationMessages: unknown
    ): {
        error?: boolean;
        errorMessages?: string;
        "error-messages"?: string;
    } {
        if (errorType == "error") {
            return {
                error: this.getErrorCount(name, this.$validator.errors),
            };
        }
        if (errorType == "error-messages" || errorType == "errorMessages") {
            return {
                errorMessages: this.getErrorValue(
                    name,
                    this.$validator.errors,
                    validationMessages
                ),
            };
        }
        return {};
    }
}

export default CommonErrorMethods;
