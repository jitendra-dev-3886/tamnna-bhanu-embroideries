<template>
    <div class="d-flex flex-column flex-root">
        <div
            id="kt_login"
            class="
                login login-1 login-signin-on
                d-flex
                flex-column flex-lg-row flex-row-fluid
                bg-white
            "
        >
            <!--begin::Aside-->
            <div
                class="
                    login-aside
                    d-flex
                    flex-row-auto
                    bgi-size-cover bgi-no-repeat
                    p-10 p-lg-15
                "
               style="background-color: black;"
            >
                <!--begin: Aside Container --  :style="{ backgroundImage: `url(${backgroundImage})` }"-->
                <div
                    class="
                        d-flex
                        flex-row-fluid flex-column
                        justify-content-between
                    "
                >
                    <!--begin: Aside header -->

                    <!--end: Aside header -->
                    <!--begin: Aside content -->
                    <div
                        class="
                            flex-column-fluid
                            d-flex
                            flex-column
                            justify-content-center
                        "
                    >
                    <a href="#" class="flex-column-auto">
                        <img :src="$getConst('LOGO_IMG')" width="75%" height="75%">
                    </a>
                        <h2 class="font-size-h1 mt-10 mb-5 text-white" style="margin-left: 30px;">
                            Welcome to Bhanu Embroideries!
                        </h2>
                    </div>
                    <!--end: Aside content -->
                    <!--begin: Aside footer for desktop -->
                    <div
                        class="
                            d-none
                            flex-column-auto
                            d-lg-flex
                            justify-content-between
                            mt-15
                        "
                    >
                        <div class="opacity-70 font-weight-bold text-white" style="color:#ead09d">
                            © {{ currentYear }} Bhanu Embroideries
                        </div>
                    </div>
                    <!--end: Aside footer for desktop -->
                </div>
                <!--end: Aside Container -->
            </div>
            <!--begin::Aside-->

            <!--begin::Content-->
            <div
                class="
                    flex-row-fluid
                    d-flex
                    flex-column
                    position-relative
                    p-7
                    overflow-hidden
                "
            >
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <router-view />
                </div>
            </div>
            <!--end::Content-->
        </div>
    </div>
</template>

<!-- Load login custom page styles -->
<style lang="scss">
/*@import "../../../../assetsassets/sass/pages/login/login-1.scss";*/
</style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";

@Component
class Auth extends Vue {
    public currentYear: string | number = "";

    get backgroundImage(): string {
        return "images/bg-4.jpg";
    }

    mounted(): void {
        const d = new Date();
        this.currentYear = d.getFullYear();
    }
}

export default Auth;
</script>
