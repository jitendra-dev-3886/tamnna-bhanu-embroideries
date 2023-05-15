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
                <!--begin: Aside Container -->
                <div
                    class="
                        d-flex
                        flex-row-fluid flex-column
                        justify-content-between
                    "
                >
                    <!--begin: Aside header -->
                    <a href="#" class="flex-column-auto">
                        <img :src="$getConst('LOGO_IMG')" width="75%" height="75%">
                    </a>
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
                        <h3 class="font-size-h1 mt-10 mb-5 text-white">
                            Welcome to Bhanu Embroideries!
                        </h3>
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
                        <div class="opacity-70 font-weight-bold text-white">
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
                    <v-flex xs12 sm8 md5 lg6 xl6>
                        <div
                            class="
                                text-center
                                mt-15
                                pb-12
                                pa-6
                                grid-view
                                brows-job-list
                            "
                        >
                            <h1
                                class="
                                    font-size-sm-100 font-weight-boldest
                                    text-dark-75
                                    mt-15
                                "
                                style="font-size: 150px"
                            >
                                Sorry!
                            </h1>
                            <h5 class="font-20 mb-4 mt-4">
                                We can’t find the page you’re looking
                                for.
                            </h5>
                            <v-btn
                                height="50"
                                color="primary"
                                :to="`/${
                                    UserData && UserData.authorization
                                    ? 'dashboard'
                                    : ''
                                }`"
                            >
                            <span style="color: white;font-size: 30px;font-weight: bold;">Go Back</span>
                            </v-btn>
                        </div>
                    </v-flex>
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
import CommonServices from "../mixins/common";
import { Component, Mixins, Prop } from "vue-property-decorator";

@Component
class PageNotFound extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    currentYear: string | number = "";

    get backgroundImage(): string {
        return "images/bg-4.jpg";
    }

    mounted(): void {
        const d = new Date();
        this.currentYear = d.getFullYear();
    }
}

export default PageNotFound;
</script>
