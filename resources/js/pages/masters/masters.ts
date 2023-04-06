import Vue from 'vue';

// vuetify conflict style issue
// https://github.com/vuetifyjs/vuetify/issues/8530

// workaround
// https://github.com/vuetifyjs/vuetify/issues/9999
const ignoreWarnMessage = 'The .native modifier for v-on is only valid on components but it was used on <div>.';
// eslint-disable-next-line no-unused-vars
Vue.config.warnHandler = function (msg: any, vm: any, trace:any) {
  if (msg === ignoreWarnMessage) {
    msg = null;
    vm = null;
    trace = null;
  }
};

export default {
  components: {},
};
