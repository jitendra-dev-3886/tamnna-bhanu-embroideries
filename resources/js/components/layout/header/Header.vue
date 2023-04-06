<template>
    <!-- begin:: Header -->
    <div id="kt_header" ref="kt_header" class="header" :class="headerClasses">
        <div
            class="
                container-fluid
                d-flex
                align-items-center
                justify-content-between
            "
        >
            <!-- begin:: Header Menu -->
            <div
                ref="kt_header_menu_wrapper"
                class="header-menu-wrapper header-menu-wrapper-left"
            >
                <div
                    v-if="headerMenuEnabled"
                    id="kt_header_menu"
                    ref="kt_header_menu"
                    class="header-menu header-menu-mobile"
                    :class="headerMenuClasses"
                >
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <h5 class="text-dark font-weight-bold my-2 mr-7">
                            <!--                <v-icon color="#007190">{{ icons.mdiViewDashboard }}</v-icon>-->
                            <!-- <img src="/images/vehicle.png" class="mr-1 heading-img"> -->
                            <template v-if="getPageTitle">
                                <span class="m-0" v-html="getPageTitle" />
                            </template>
                        </h5>
                        <!--<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2">
                            <li class="breadcrumb-item">
                                <a href="#/" class="subheader-breadcrumbs-home router-link-active">
                                    <i class="flaticon2-shelter text-muted icon-1x"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><span class="text-muted"> Vehicles </span>
                            <li class="breadcrumb-item"><span class="text-muted"> Level 1 </span></li>
                        </ul>-->
                    </div>
                    <!-- <v-btn-toggle
                        v-model="toggle_exclusive" class="nav-header"
                    >
                        <v-btn>
                            <a href="#" class="menu-link"><span class="menu-text"> Masters Library</span></a>
                        </v-btn>
                        <v-btn>
                            <a href="#" class="menu-link"><span class="menu-text"> Subscriptions </span></a>
                        </v-btn>
                        <v-btn>
                            <a href="#" class="menu-link"><span class="menu-text"> Portal settings </span></a>
                        </v-btn>
                        <v-btn>
                            <a href="#" class="menu-link"><span class="menu-text"> Reports </span></a>
                        </v-btn>
                    </v-btn-toggle> -->
                    <!-- example static menu here -->
                </div>
            </div>
            <!-- end:: Header Menu -->
            <KTTopbar />
        </div>
        <snackbar v-model="snackbar" />
        <permission-dialog v-model="permissionDialog" />
    </div>
    <!-- end:: Header -->
</template>
<script lang="ts">
    import KTTopbar from "./Topbar.vue";
    import KTLayoutHeader from "../../../../assets/js/layout/base/header.js";
    import KTLayoutHeaderMenu from "../../../../assets/js/layout/base/header-menu.js";
    import Snackbar from "@/components/Snackbar.vue";
    import PermissionDialog from "@/components/PermissionDialog.vue";
    import { ConfigModule } from "@/store/config";
    import { HTMLClassModule } from "@/store/htmlclass";
    import { Component, Vue } from "vue-property-decorator";
    import { SnackbarModule } from "@/store/snackbar";
    import { PermissionModule } from "@/store/permission";
    @Component({
        components: {
            KTTopbar,
            Snackbar,
            PermissionDialog,
        },
    })
    class KTHeader extends Vue {
        get permissionDialog() {
            return PermissionModule.permissionDialog;
        }
        get snackbar() {
            return SnackbarModule.snackbar;
        }
        /**
         * Check if the header menu is enabled
         * @returns {boolean}
         */
        get headerMenuEnabled() {
            return !!ConfigModule.layoutConfig("header.menu.self.display");
        }
        /**
         * Get extra classes for header based on the options
         * @returns {null|*}
         */
        get headerClasses() {
            const classes = HTMLClassModule.getClasses("header");
            if (typeof classes !== "undefined") {
                return classes.join(" ");
            }
            return null;
        }
        /**
         * Get extra classes for header menu based on the options
         * @returns {null|*}
         */
        get headerMenuClasses() {
            const classes = HTMLClassModule.getClasses("header_menu");
            if (typeof classes !== "undefined") {
                return classes.join(" ");
            }
            return null;
        }
        /**
         * get page title from router
         * @returns {function(*): *}
         */
        get getPageTitle() {
            return this.$route.meta?.pageTitle;
        }
        mounted() {
            // Init Desktop & Mobile Headers
            KTLayoutHeader.init("kt_header", "kt_header_mobile");
            // Init Header Menu
            KTLayoutHeaderMenu.init(
                this.$refs.kt_header_menu,
                this.$refs.kt_header_menu_wrapper
            );
        }
    }
    export default KTHeader;
</script>
