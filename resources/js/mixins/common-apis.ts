import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "./common";
import { HTMLClassModule } from "../store/htmlclass";
import { IObject } from "../../assets/types/common";
import { AxiosResponse } from "axios";
import { rejects } from "assert";

@Component
class CommonApis extends Mixins(CommonServices) {
    /**
     * Reset Store before opening Add/Edit Page
     */
    resetStoreData(storeName: string): Promise<unknown> {
        return new Promise((resolve, reject) => {
            this.onModalClear(storeName, "CLEAR_STORE");
            if (this["$route"].params.id && this["$route"].params.id != "") {
                HTMLClassModule.addBodyClassName("page-loading");
                this.$store.commit(
                    `${storeName}/SET_EDIT_ID`,
                    this["$route"].params.id
                );

                this.$store
                    .dispatch(`${storeName}/getById`, this["$route"].params.id)
                    .then(
                        (response) => {
                            HTMLClassModule.removeBodyClassName("page-loading");
                            this.$validator.reset();

                            resolve(response);
                        },
                        (error) => {
                            HTMLClassModule.removeBodyClassName("page-loading");
                            this.showDialog(
                                this.getAPIErrorMessage(error.response)
                            );
                            if (error.response) {
                                reject(error.response);
                            }
                            reject(error);
                        }
                    );
            } else {
                //reject(this["$getConst"]("MISSING_PARAM_ID"));
            }
        });
    }

    /**
     * Call getById API and manage the promise
     * @param {*} id
     * @param {*} storeName
     */
    getSingleRecord(id: string | number, storeName: string): Promise<IObject> {
        HTMLClassModule.addBodyClassName("page-loading");
        this.$store.commit(`${storeName}/SET_EDIT_ID`, id);
        return new Promise((resolve, reject) => {
            this.$store
                .dispatch(`${storeName}/getById`, id)
                .then((response) => {
                    const obj = response.data;
                    if (
                        !Object.prototype.hasOwnProperty.call(response, "data")
                    ) {
                        reject(response);
                    }
                    resolve(obj);
                })
                .catch((e) => {
                    if (e.response) {
                        reject(e.response);
                    }
                    reject(e);
                });
        });
    }
}
export default CommonApis;
