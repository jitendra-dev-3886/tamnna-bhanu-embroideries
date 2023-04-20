<template>
    <div class="d-flex flex-column flex-root">
        <!-- begin:: Header Mobile -->
        <KTHeaderMobile />
        <!-- end:: Header Mobile -->

        <Loader v-if="loaderEnabled" :logo="loaderLogo" />

        <!-- begin::Body -->
        <div class="d-flex flex-row flex-column-fluid page">
            <!-- begin:: Aside Left -->
            <KTAside v-if="asideEnabled" />
            <!-- end:: Aside Left -->

            <div
                id="kt_wrapper"
                class="d-flex flex-column flex-row-fluid wrapper"
            >
                <!-- begin:: Header -->
                <KTHeader />
                <!-- end:: Header -->

                <!-- begin:: Content -->
                <div
                    id="kt_content"
                    class="content d-flex flex-column flex-column-fluid pt-0"
                >
                    <!-- begin:: Content Head -->

                    <!-- begin:: Content Body -->
                    <div class="d-flex flex-column-fluid">
                        <div
                            class="main-container"
                            :class="{
                                'container-fluid': contentFluid,
                                container: !contentFluid,
                            }"
                        >
                            <transition name="fade-in-up">
                                <router-view />
                            </transition>
                        </div>
                    </div>
                </div>
                <!--                <KTFooter/>-->
            </div>
        </div>
        <KTScrollTop />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import KTAside from "@/components/layout/aside/Aside.vue";
import KTHeader from "@/components/layout/header/Header.vue";
import KTHeaderMobile from "@/components/layout/header/HeaderMobile.vue";
import KTFooter from "@/components/layout/footer/Footer.vue";
import KTScrollTop from "@/components/layout/extras/ScrollTop.vue";
import Loader from "@/components/layout/extras/Loader.vue";
import HtmlClass from "@/common_services/services/htmlclass.service.js";
import { ConfigModule } from "@/store/config";
import { HTMLClassModule } from "@/store/htmlclass";
import { BreadcrumbModule } from "@/store/breadcrumbs";
import { IObject } from "../../../assets/types/common";

@Component({
    components: {
        KTAside,
        KTHeader,
        KTHeaderMobile,
        KTFooter,
        KTScrollTop,
        Loader,
    },
})
class Layout extends Vue {
    beforeMount(): void {
        // show page loading
        //HTMLClassModule.addBodyClassName("page-loading");

        // initialize html element classes
        HtmlClass.init(ConfigModule.layoutConfig());
    }

    get breadcrumbs(): IObject[] {
        return BreadcrumbModule.breadcrumbs;
    }

    get pageTitle(): string {
        return BreadcrumbModule.pageTitle;
    }

    /**
     * Check if the page loader is enabled
     * @returns {boolean}
     */
    get loaderEnabled(): boolean {
        return !/false/.test(ConfigModule.layoutConfig("loader.type"));
    }

    /**
     * Check if container width is fluid
     * @returns {boolean}
     */
    get contentFluid(): boolean {
        return ConfigModule.layoutConfig("content.width") === "fluid";
    }

    /**
     * Page loader logo image using require() function
     * @returns {string}
     */
    get loaderLogo(): string {
        return process.env.BASE_URL + ConfigModule.layoutConfig("loader.logo");
    }

    /**
     * Check if the left aside menu is enabled
     * @returns {boolean}
     */
    get asideEnabled(): boolean {
        return !!ConfigModule.layoutConfig("aside.self.display"); // Simplified of if else condition
    }

    /**
     * Set the right toolbar display
     * @returns {boolean}
     */
    get toolbarDisplay(): boolean {
        // return !!ConfigModule.layoutConfig("toolbar.display");
        return true;
    }

    /**
     * Set the subheader display
     * @returns {boolean}
     */
    get subheaderDisplay(): boolean {
        return !!ConfigModule.layoutConfig("subheader.display");
    }
}

export default Layout;
</script>
