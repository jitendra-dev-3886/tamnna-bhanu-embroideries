<template>
    <!-- begin:: Aside -->
    <div
        id="kt_aside"
        ref="kt_aside"
        class="aside aside-left aside-fixed d-flex flex-column flex-row-auto"
        @mouseover="mouseEnter"
        @mouseleave="mouseLeave"
        style="overflow: auto"
    >
        <!-- begin:: Aside -->
        <KTBrand />
        <!-- end:: Aside -->

        <!-- begin:: Aside Menu -->
        <div
            id="kt_aside_menu_wrapper"
            class="aside-menu-wrapper flex-column-fluid"
            style="background-color: black;"
        >
            <div
                id="kt_aside_menu"
                ref="kt_aside_menu"
                class="aside-menu my-4"
                data-menu-vertical="1"
                data-menu-dropdown-timeout="500"
                :class="asideMenuClass"
            >
                <!-- example static menu here -->
                <perfect-scrollbar
                    class="aside-menu scroll"
                    style="max-height: 90vh; position: relative;"
                >
                    <KTMenu />
                </perfect-scrollbar>
            </div>
        </div>
        <!-- <div class="aside-footer-logo brand">
            <div class="brand-logo">
                <img
                    :src="$getConst('LOGO_IMG')"
                    class=""
                    style="width: 95px;"
                />
            </div>
        </div> -->
    </div>
    <!-- end:: Aside -->
</template>

<script lang="ts">
import KTBrand from "../brand/Brand.vue";
import KTLayoutAside from "../../../../assets/js/layout/base/aside.js";
import KTLayoutAsideMenu from "../../../../assets/js/layout/base/aside-menu.js";
import KTMenu from "./Menu.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import { Component, Vue } from "vue-property-decorator";

@Component({
    components: {
        KTBrand,
        KTMenu
    }
})
class KTAside extends Vue {
    public insideTm: number | null = 0;
    public outsideTm: number | null = 0;
    mounted() {
        this.$nextTick(() => {
            // Init Aside
            KTLayoutAside.init(this.$refs.kt_aside);

            // Init Aside Menu
            KTLayoutAsideMenu.init(this.$refs.kt_aside_menu);
        });
    }

    /**
     * Use for fixed left aside menu, to show menu on mouseenter event.
     */
    mouseEnter() {
        // check if the left aside menu is fixed
        if (document.body.classList.contains("aside-fixed")) {
            if (this.outsideTm) {
                clearTimeout(this.outsideTm);
                this.outsideTm = null;
            }

            // if the left aside menu is minimized
            if (document.body.classList.contains("aside-minimize")) {
                document.body.classList.add("aside-minimize-hover");

                // show the left aside menu
                document.body.classList.remove("aside-minimize");
            }
        }
    }

    /**
     * Use for fixed left aside menu, to show menu on mouseenter event.
     */
    mouseLeave() {
        if (document.body.classList.contains("aside-fixed")) {
            if (this.insideTm) {
                clearTimeout(this.insideTm);
                this.insideTm = null;
            }

            // if the left aside menu is expand
            if (document.body.classList.contains("aside-minimize-hover")) {
                // hide back the left aside menu
                document.body.classList.remove("aside-minimize-hover");
                document.body.classList.add("aside-minimize");
            }
        }
    }
    /**
     * Get extra classes for menu based on the options
     */
    get asideMenuClass() {
        const classes = HTMLClassModule.getClasses("aside_menu");
        if (typeof classes !== "undefined") {
            return classes.join(" ");
        }
        return null;
    }
}
export default KTAside;
</script>
