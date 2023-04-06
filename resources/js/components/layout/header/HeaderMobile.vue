<template>
    <div
        id="kt_header_mobile"
        class="header-mobile align-items-center"
        :class="headerClasses"
    >
        <!--begin::Logo-->
        <a href="/">
            <img
                class="width-40"
                alt="Logo"
                src="../../../../assets/images/logo.png"
            >
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Aside Mobile Toggle-->
            <button
                v-if="asideEnabled"
                id="kt_aside_mobile_toggle"
                class="btn p-0 burger-icon burger-icon-left"
            >
                <span />
            </button>
            <!--end::Aside Mobile Toggle-->
            <!--begin::Topbar Mobile Toggle-->
            <button
                id="kt_header_mobile_topbar_toggle"
                ref="kt_header_mobile_topbar_toggle"
                class="btn btn-hover-text-primary p-0 ml-2"
            >
        <span class="svg-icon svg-icon-xl">
          <!--begin::Svg Icon | path:svg/icons/General/User.svg-->
          <inline-svg
              class="svg-icon"
              src="../media/svg/icons/General/User.svg"
          />
            <!--end::Svg Icon-->
        </span>
            </button>
            <!--end::Topbar Mobile Toggle-->
        </div>
        <!--end::Toolbar-->
    </div>
</template>

<script lang="ts">
    import KTLayoutHeaderTopbar from '../../../../assets/js/layout/base/header-topbar.js';
    import {ConfigModule} from "@/store/config";
    import {HTMLClassModule} from "@/store/htmlclass";
    import {Component, Vue} from 'vue-property-decorator'
    @Component
    class KTHeaderMobile extends Vue {
        mounted() {
            // Init Header Topbar For Mobile Mode
            KTLayoutHeaderTopbar.init(this.$refs.kt_header_mobile_topbar_toggle);
        }
        /**
         * Get header logo
         * @returns {string}
         */
        get headerLogo() {
            return process.env.BASE_URL + ConfigModule.layoutConfig('self.logo.dark');
        }

        /**
         * Get classes for mobile header
         * @returns {null|*}
         */
        get headerClasses() {
            const classes = HTMLClassModule.getClasses('header_mobile');
            if (typeof classes !== 'undefined') {
                return classes.join(' ');
            }
            return null;
        }

        /**
         * Check if the left aside menu is enabled
         * @returns {boolean}
         */
        get asideEnabled() {
            return !!ConfigModule.layoutConfig('aside.self.display');
        }
    }
    export default KTHeaderMobile;

</script>
