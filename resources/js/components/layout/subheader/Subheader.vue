<template>
    <div
        class="subheader py-2 py-lg-4"
        v-bind:class="subheaderClasses"
        id="kt_subheader"
    >
        <div
            class="
                d-flex
                align-items-center
                justify-content-between
                flex-wrap flex-sm-nowrap
            "
            v-bind:class="{
                'container-fluid': widthFluid,
                container: !widthFluid,
            }"
        >
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h5 class="text-dark font-weight-bold my-2 mr-7">
                    <!--                <v-icon color="#007190">{{ icons.mdiViewDashboard }}</v-icon>-->
                    <img src="/images/vehicle.png" class="mr-1 heading-img" />
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
        </div>
    </div>
</template>

<style lang="scss">
    .custom-v-dropdown {
        &.dropdown-toggle {
            padding: 0;

            &:hover {
                text-decoration: none;
            }

            &.dropdown-toggle-no-caret {
                &:after {
                    content: none;
                }
            }
        }

        &.dropdown-menu {
            margin: 0;
            padding: 0;
            outline: none;

            .b-dropdown-text {
                padding: 0;
            }
        }
    }
</style>

<script lang="ts">
    import CommonServices from "@/mixins/common";
    import { Component, Mixins, Prop } from "vue-property-decorator";
    import { IObject } from "../../../../assets/types/common";
    import { ConfigModule } from "../../../store/config";
    @Component
    class KTSubheader extends Mixins(CommonServices) {
        @Prop({ default: [] }) breadcrumbs!: IObject[];
        @Prop({ default: "" }) title!: string;
        /**
         * Check if subheader width is fluid
         */
        get widthFluid(): boolean {
            return ConfigModule.layoutConfig("subheader.width") === "fluid";
        }

        get subheaderClasses(): string {
            const classes = [];
            const style = ConfigModule.layoutConfig("subheader.style");
            if (style) {
                classes.push(style);

                if (style === "solid") {
                    classes.push("bg-white");
                }

                if (ConfigModule.layoutConfig("subheader.fixed")) {
                    classes.push("border-top");
                }
            }
            return classes.join(" ");
        }
    }

    export default KTSubheader;
