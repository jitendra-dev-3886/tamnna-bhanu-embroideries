<template>
    <!-- begin:: Aside -->
    <div id="kt_brand" ref="kt_brand" class="brand flex-column-auto" style="background-color: black;">
        <div class="brand-logo">
            <router-link to="/users">
                <img class="width-80" :src="$getConst('LOGO_IMG_TITLE')" alt="Logo"/>
            </router-link>
        </div>
        <div v-if="allowMinimize" class="brand-tools">
            <button
                id="kt_aside_toggle"
                ref="kt_aside_toggle"
                class="brand-toggle btn btn-sm px-0"
            >
                <span class="svg-icon svg-icon svg-icon-xl">
                    <inline-svg
                        class="svg-icon"
                        src="../media/svg/icons/Navigation/Angle-double-left.svg"
                    />
                </span>
            </button>
        </div>
    </div>
    <!-- end:: Aside -->
</template>

<style lang="scss" scoped>
    .aside-toggle {
        outline: none;
    }
</style>

<script lang="ts">
    import objectPath from 'object-path';
    import { ConfigModule } from '@/store/config';
    import { Component, Vue } from 'vue-property-decorator';
    import KTLayoutBrand from '../../../../assets/js/layout/base/brand.js';
    import KTLayoutAsideToggle from '../../../../assets/js/layout/base/aside-toggle.js';

    @Component
    class KTBrand extends Vue {
        mounted() {
            // Init Brand Panel For Logo
            KTLayoutBrand.init(this.$refs.kt_brand);

            // Init Aside Menu Toggle
            KTLayoutAsideToggle.init(this.$refs.kt_aside_toggle);
        }

        get allowMinimize() {
            return !!ConfigModule.layoutConfig('aside.self.minimize.toggle');
        }

        siteLogo() {
            const menuAsideLeftSkin = ConfigModule.layoutConfig('brand.self.theme');
            // set brand logo
            const logoObject = ConfigModule.layoutConfig('self.logo');

            let logo;
            if (typeof logoObject === 'string') {
                logo = logoObject;
            }
            if (typeof logoObject === 'object') {
                logo = objectPath.get(logoObject, `${menuAsideLeftSkin}`);
            }
            if (typeof logo === 'undefined') {
                const logos = ConfigModule.layoutConfig('self.logo');
                logo = logos[Object.keys(logos)[0]];
            }
            return process.env.BASE_URL + logo;
        }
    }

    export default KTBrand;
</script>
