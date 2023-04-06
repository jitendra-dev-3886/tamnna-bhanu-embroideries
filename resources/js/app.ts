import Vue from "vue";
import Vuex from "vuex";

// router store and plugins
import "babel-polyfill";
import VueRouter from "vue-router";
import IdleVue from "idle-vue";
import VeeValidate from "vee-validate";
import Bugsnag from "@bugsnag/js";
import BugsnagPluginVue from "@bugsnag/plugin-vue";

// theme support plugin
import "./plugins/metronic";
import InlineSvg from "vue-inline-svg";
import PerfectScrollbar from "vue2-perfect-scrollbar";
import "vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css";
import Echo from "laravel-echo";
import router from "./router";
import KTUtil from "../assets/js/components/util";
// import registerStoreModule from './common_services/register-store-module';
import vuetify from "./plugins/vuetify";
import Constants from "./constants/constant-plugin";
import store from "./store/store";
import App from "./pages/App.vue";
// Permission directives
import hasPermission from "./directives/permission-directives";
import { UserModule } from "./store/user";
import "./bootstrap";
import { VueReCaptcha } from "vue-recaptcha-v3";
import VueMce from "vue-mce";

if (process.env.MIX_MODE == "production") {
    // const bugsnagClient = bugsnag({
    //     apiKey: process.env.MIX_BUGSNAG_API_KEY || "",
    // });
    // bugsnagClient.use(bugsnagVue, Vue);
    Bugsnag.start({
        apiKey: process.env.MIX_BUGSNAG_API_KEY || "",
        plugins: [new BugsnagPluginVue()],
    });

    const bugsnagVue = Bugsnag.getPlugin("vue");
    bugsnagVue?.installVueErrorHandler(Vue);
}
window["KTUtil"] = KTUtil;
window["PerfectScrollbar"] = PerfectScrollbar;

// Pusher configuration :: Begin
const authorizationToken = UserModule.currentUserData
    ? UserModule.currentUserData.authorization
    : "";
window["Pusher"] = require("pusher-js");

window["Echo"] = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            Authorization: `Bearer ${authorizationToken}`,
        },
    },
});
// Pusher configuration :: end

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VeeValidate);
Vue.use(Constants); // Constants as plugin
// Vue.mixin(registerStoreModule);

Vue.component("inline-svg", InlineSvg);
Vue.use(VueMce);
// Perfect scrollbar
Vue.use(PerfectScrollbar);
Vue.directive("store", hasPermission); // create
Vue.directive("index", hasPermission); // display a listing
Vue.directive("canAccess", hasPermission); //  display a single row
Vue.directive("update", hasPermission); // update
Vue.directive("destroy", hasPermission); // delete
Vue.directive("export", hasPermission); // export
Vue.directive("importBulk", hasPermission); // import 
Vue.directive('deleteGallery', hasPermission); // delete directive
Vue.directive('deleteProductId', hasPermission); // delete directive
Vue.directive("getPermissionsByRole", hasPermission); // get permission by role
Vue.directive("setUnsetPermissionToRole", hasPermission); // set unset permission
Vue.directive("changePassword", hasPermission); // change password
Vue.directive("deleteAll", hasPermission); // delete all functionality

if (process.env.MIX_GOOGLE_CAPTCHA_KEY) {
    // vue recaptcha
    Vue.use(VueReCaptcha, {
        siteKey: process.env.MIX_GOOGLE_CAPTCHA_KEY,
        loaderOptions: {
            autoHideBadge: true,
            explicitRenderParameters: {
                size: "invisible",
            },
        },
    });
}

// eslint-disable-next-line no-unused-vars
const app = new Vue({
    router,
    vuetify,
    store,
    render: (h) => h(App),
}).$mount("#appMain");

/** **Screen off after certain time*** */
const eventsHub = new Vue();
Vue.use(IdleVue, { eventEmitter: eventsHub, idleTime: 600000 });
/** **Screen off after certain time*** */

const version = "__VERSION__";
export default { version };
