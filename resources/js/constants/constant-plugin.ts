/* //https://stackoverflow.com/questions/42662144/how-could-i-use-const-in-vue-template
//https://dev.to/nkoik/writing-a-very-simple-plugin-in-vuejs---example-8g8 */
import { PluginObject } from "vue";
import PERMISSION_CONSTANTS from "./permission-constants";

// export default {
//   install(Vue) {
//     Vue.prototype.$getConst = (key) => YOUR_CONSTS[key];
//   },
// };
//
const YOUR_CONSTS = {
    DATE_CONST: "dd-MM-yyyy",
    TIME_CONST: "hh:mm:ss a",
    FILTER_DATE_CONST: "yyyy-MM-dd",
    DATE_TIME_CONST: "dd-MM-yyyy hh:mm:ss a",
    FILTER_DATE_TIME_CONST: "yyyy-MM-dd hh:mm:ss a",
    CREATE_ACTION: "Added successfully",
    UPDATE_ACTION: "Edited successfully",
    DELETE_ACTION: "Deleted successfully",
    DELETE_WARNING: "Are you sure you want to delete?",
    MULTIPLE_DELETE_WARNING_START: "Are you sure you want to delete ",
    MULTIPLE_DELETE_WARNING_END: " items?",
    REGISTER_SUCCESS:
        "Successfully registered. Please check your email for verification",
    BTN_CANCEL: "Cancel",
    BTN_SUBMIT: "Submit",
    BTN_CONTINUE: "Continue",
    BTN_UPDATE: "Update",
    BTN_OK: "Ok",
    BTN_DELETE: "Delete",
    BTN_CLOSE: "Close",
    DELETE_TITLE: "Delete Confirmation",
    FILE_UPLOADED: "File uploaded successfully",
    DOWNLOAD_SAMPLE_CSV: "Sample CSV downloaded successfully",
    DOWNLOAD_CSV: "Downloaded successfully",
    ROLE_TITLE: "Add Role",
    ROLE_DESC: "Please Enter Your Role",
    WARNING: "Are you sure you want to delete this record?",
    EMAIL_SEND_MESSAGE: "Email sent successfully",
    RESET_PASSWORD: "Password reset successfully",
    CHANGED_PASSWORD: "Password changed successfully",
    NOIMAGE: "No Image Found",
    TXT_UPDATE: "Update",
    TXT_ADD: "Add",
    TXT_CREATE: "Create",
    FILE_UPLOAD_MSG: "Drag and drop your csv file",
    ZIP_FILE_UPLOAD_MSG: "Drag and drop your zip",
    SELECT_FILE_LABEL: "Click here to upload csv file",
    SELECT_ZIP_LABEL: "Click here to upload zip",

    MANAGE_IMAGES:"Manage Images",
    VIEW_PRODUCT_GALLERY_TOOLTIP:"View Gallery",
    COLUMN_TOOLTIP: "Columns Visibility",
    VIEW_DETAILS_TOOLTIP: "View Details",
    DOWNLOAD_TOOLTIP: "Download",
    EDIT_TOOLTIP: "Edit",
    EDIT_IMAGE_TOOLTIP:"Edit Image",
    DELETE_TOOLTIP: "Delete",
    FILTER_TOOLTIP: "Filter",
    APPROVE_CUSTOMER_TOOLTIP:"Approve",
    LOGGOFF_TEXT:
        "You have been logged off. Please enter your password to login.",
    SUPER_ADMIN_DELETE_WARNING: "Super admin can not be deleted.",
    ADMIN_DELETE_WARNING: "Admin role can not be deleted.",
    LOGO_IMG: "/images/Logo A.jpg",
    LOGO_IMG_TITLE:"/images/Logo A-title.jpg" ,
    MISSING_PARAM_ID: "Param ID is missing in route",
    SESSION_EXPIRED: "Your session has expired! please login again.",

    DASHBOARD_REDIRECT_CUSTOMERS:1,
    DASHBOARD_REDIRECT_CATEGORIES:2,
    DASHBOARD_REDIRECT_PRODUCTS:3,
    DASHBOARD_REDIRECT_ORDERS:4,

    DASHBOARD_STATUS_COLORS: {
        "0": "warning",
        "1": "error",
        "2": "success",
        "3": "error",
        "4": "error",
    },
    DASHBOARD_PRODUCT_STATUS: [
        { value: "0", label: "Out of Stock" },
        { value: "1", label: "Available" },
        { value: "2", label: "Total Number" },

    ],
    DASHBOARD_ORDER_STATUS_TYPE_TEXT: {
        "0": "Pending",
        "1": "Delivered",
        "2": "Total Number",
        "3": "Shipped",
        "4": "Returned",
    },
    DASHBOARD_ORDER_STATUS_VALUES: {
        Pending: "0",
        Delivered: "1",
        TotalNumber: "2",
        Shipped: "3",
        Returned: "4",
    },

    //  Broadcasting public channel events constant::Begin
    PUBLIC_CHANNELS: [
],

    //  Broadcasting private channel events constant::Begin
    PRIVATE_CHANNELS: [
],
    ADD_OPERATION: 1,
    EDIT_OPERATION: 2,
    REMOVE_OPERATION: 3,
    REMOVE_MULTIPLE_OPERATION: 4,
    //  Broadcasting channel events constant::End

    ...PERMISSION_CONSTANTS,
};

const Constants: PluginObject<any> = {
    install: (Vue) => {
        Vue.prototype.$getConst = (key: string) => YOUR_CONSTS[key];
    },
};

export default Constants;
// export default new (class Constants {
//     public install(Vue: any) {
//         Vue.prototype.$getConst = (key: string) => YOUR_CONSTS[key];
//     }
// })();
