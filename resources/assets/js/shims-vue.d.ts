import Vue from 'vue';
import VueRouter, { Route } from 'vue-router';
import {Validator} from "vee-validate";
declare module 'vue/types/vue' {
    interface Vue {
        $router: VueRouter;
        $route: Route;
        $getConst: (key: string) => any;
        $validator: Validator;
    }
}

import store from "@/store/store";
import { ReCaptchaInstance } from 'recaptcha-v3'
declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $store: typeof store;
        $recaptcha: (action: string) => Promise<string>
        $recaptchaLoaded: () => Promise<boolean>
        $recaptchaInstance: ReCaptchaInstance
    }
}
