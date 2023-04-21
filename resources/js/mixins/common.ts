import {
    mdiClose,
    mdiDelete,
    mdiDownload,
    mdiExport,
    mdiEye,
    mdiFilter,
    mdiImage,
    mdiPaperclip,
    mdiPencil,
    mdiPlus,
    mdiUpload,
    mdiImageEditOutline
} from "@mdi/js";
import Component, { mixins } from "vue-class-component";
import Echo from "laravel-echo";
import { Validator } from "vee-validate";
import CommonDateMethods from "./common-date-methods";
import CommonErrorMethods from "./common-error-methods";
import { SnackbarModule } from "../store/snackbar";
import { UserModule } from "../store/user";
import { getTime } from "date-fns";
import { IObject, IPuhserChannels } from "../../assets/types/common";
import { IFilterModel } from "../../assets/types/table";
import { ICurrentUserData } from "../../assets/types/user";

@Component({
    filters: {
        /**
         * Truncate no of character from the text
         * @param value - text
         * @param limit - no of chars which need to remove
         * @returns {string} - Truncated text
         */
        truncateText(value, limit) {
            return value.length > limit
                ? `${value.substring(0, limit - 3)}...`
                : value;
        }
    }
})
class CommonServices extends mixins(CommonDateMethods, CommonErrorMethods) {
    isSubmitting = false;

    errorMessage = "";

    icons = {
        mdiPencil,
        mdiDelete,
        mdiFilter,
        mdiPaperclip,
        mdiExport,
        mdiClose,
        mdiPlus,
        mdiEye,
        mdiDownload,
        mdiUpload,
        mdiImage,
        mdiImageEditOutline
    };

    editorConfig: IObject = {
        height: 250,
        inline: false,
        theme: "silver",
        fontsize_formats:
            "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 34px 38px 42px 48px 54px 60px",
        plugins:
            "print preview fullpage code searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools colorpicker textpattern help",
        toolbar1:
            "formatselect fontsizeselect | code | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat",
        image_advtab: true,
        charCounterCount: false
    };

    get UserData(): ICurrentUserData {
        return UserModule.currentUserData;
    }

    /**
     * clear object Method
     * @param object
     */
    clearObject(object: IObject): void {
        Object.keys(object).forEach(key => {
            delete object[key];
        });
    }

    /**
     * @objectData Object of data from which we need to filter
     * @returns {Array list of filtered items}
     * @param objectData
     * @param param
     */
    filter(objectData: IObject[], param: IObject[]): IObject[] {
        const filterData: Array<IObject> = [];
        Object.keys(param).forEach(key => {
            objectData.filter((item: IObject) => {
                if (item[key] == param[key]) {
                    filterData.push(item);
                }
                return item;
            });
        });
        return filterData;
    }

    /**
     * Used for convert to CSV format
     * @param filename - name of file
     * @param data - response data
     * @param type - type of CSV
     * @param extension - extension of CSV
     */
    convertToCSV(
        filename: string,
        data: string,
        type = "text/csv;charset=utf-8;",
        extension = ".csv"
    ): void {
        const exportedFilename = `${filename}${new Date()
            .toISOString()
            .slice(0, 10)}${extension}`;
        const blob = new Blob([data], { type });
        const link = document.createElement("a");
        if (link.download !== undefined) {
            // feature detection
            // Browsers that support HTML5 download attribute
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", exportedFilename);
            link.style.visibility = "hidden";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    /**
     * Use for download a file from its url
     * @param url - url of file which needs to be downloaded
     * @param msg - msg to be displayed in toast
     */
    downloadFile(url: string, msg: string): void {
        const link = document.createElement("a");
        const filename = url.substring(url.lastIndexOf(`/${1}`));
        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = "hidden";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        SnackbarModule.setMsg(this["$getConst"](msg));
    }

    /**
     * used to check admin user is super admin or not
     * @param userData
     * @returns {boolean}
     */
    isSuperAdmin(userData: ICurrentUserData): boolean {
        return userData && userData.role_id == "1" && userData.id == "1";
    }

    /**
     * file size conversion to display
     * @param sizeInBytes
     * @returns {string}
     */
    getFileSize(sizeInBytes: number): string {
        let sizeInMB = "";
        let postfix = "";
        if (sizeInBytes / (1024 * 1024) >= 1) {
            sizeInMB = (sizeInBytes / (1024 * 1024)).toFixed(2);
            postfix = "MB";
        } else {
            sizeInMB = (sizeInBytes / 1024).toFixed(2);
            postfix = "KB";
        }
        return sizeInMB + postfix;
    }

    /**
     * Used for converting object to base64 format
     * @param filter
     */
    convetFiltertoBase64(filter: IFilterModel): string {
        if (
            filter &&
            Object.keys(filter).length > 0 &&
            filter.constructor === Object
        ) {
            return btoa(unescape(encodeURIComponent(JSON.stringify(filter))));
        }
        return "";
    }

    done24Hours(localStorageKey: string): boolean {
        if (
            localStorage.getItem(localStorageKey) &&
            localStorage.getItem(localStorageKey) != null &&
            localStorage.getItem(localStorageKey) != ""
        ) {
            const diffMinutes = this.getTimeDiff(
                getTime(new Date()),
                Number(localStorage.getItem(localStorageKey)),
                "minutes"
            );
            if (diffMinutes >= 1410) {
                localStorage.setItem(
                    localStorageKey,
                    String(getTime(new Date()))
                );
                return true;
            }
        } else {
            localStorage.setItem(localStorageKey, String(getTime(new Date())));
            return true;
        }
        return false;
    }

    subscribePrivateChannel(): void {
        // Receiving events for private channels::begin
        window["Echo"] = new Echo({
            broadcaster: "pusher",
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            forceTLS: true,
            auth: {
                headers: {
                    Authorization: `Bearer ${this.UserData.authorization}`
                }
            }
        });
        if (this.UserData && this.UserData.authorization != "") {
            this.$getConst("PRIVATE_CHANNELS").forEach(
                (item: IPuhserChannels) => {
                    window.Echo.private(item.name).listen(
                        item.event,
                        (e: IObject) => {
                            if (
                                e.operation == this.$getConst("ADD_OPERATION")
                            ) {
                                this.$store.commit(
                                    `${item.storeName}/APPEND_${item.mutationSuffix}`,
                                    e[item.responseName as string]
                                );
                            } else if (
                                e.operation == this.$getConst("EDIT_OPERATION")
                            ) {
                                this.$store.commit(
                                    `${item.storeName}/UPDATE_${item.mutationSuffix}`,
                                    e[item.responseName as string]
                                );
                            } else if (
                                e.operation ==
                                this.$getConst("REMOVE_OPERATION")
                            ) {
                                this.$store.commit(
                                    `${item.storeName}/REMOVE_${item.mutationSuffix}`,
                                    e[item.responseName as string]
                                );
                            } else if (
                                e.operation ==
                                this.$getConst("REMOVE_MULTIPLE_OPERATION")
                            ) {
                                this.$store.commit(
                                    `${item.storeName}/REMOVE_SELECTED_${item.mutationSuffix}`,
                                    e[item.responseName as string]
                                );
                            }
                        }
                    );
                }
            );
        }
        // Receiving events for private channels :: end
    }

    /**
     * Modal clear functionality
     * @param storeName
     * @param stateName
     * @param isOpen - want to open modal or not (true, false)
     */
    onModalClear(
        storeName: string,
        stateName?: string,
        isOpen?: boolean
    ): void {
        if (!stateName) {
            stateName = "CLEAR_STORE";
        }
        this["$validator"].reset();
        this.isSubmitting = false;
        this.errorMessage = "";
        this.$store.commit(`${storeName}/${stateName}`);
        if (!isOpen) {
            this.$emit("input"); // Close Pop-up
        }
    }

    beforeCreate(): void {
        // reset snackbar
        SnackbarModule.clearStore();
    }

    created(): void {
        Validator.extend("valid_file_length", {
            validate: (value, [length]: any) => Number(value.length) <= length
        });
    }
}

export default CommonServices;
