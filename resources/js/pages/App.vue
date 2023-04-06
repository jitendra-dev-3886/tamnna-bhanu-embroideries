<template>
    <v-app id="appMain" class="appMain" light>
        <router-view :key="$route.fullPath" />
        <error-modal v-model="errorDialog" :error-arr="errorArr"></error-modal>
    </v-app>
</template>

<style lang="scss">
    @import "../../assets/plugins/flaticon/flaticon.css";
    @import "../../assets/plugins/flaticon2/flaticon.css";
    @import "../../assets/plugins/keenthemes-icons/font/ki.css";
</style>
<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import { ConfigModule } from "../store/config";
    import { ErrorModule } from "../store/error";
    import ErrorModal from "../components/ErrorModal";
    import {IObject, IPuhserChannels} from "../../assets/types/common";

    @Component({
        components: {
            ErrorModal,
        },
    })
    class App extends Vue {
        get errorDialog(): boolean {
            return ErrorModule.showErrorDialog;
        }

        set errorDialog(value: boolean) {
            this.$emit("input", value);
        }

        get errorArr(): string | [] | IObject[] {
            return ErrorModule.errorArray;
        }

        mounted(): void {
            /**
             * this is to override the layout config using saved data from localStorage
             * remove this to use config only from static json (@/core/config/layout.config.json)
             */
            ConfigModule.overrideLayoutConfig();
            ErrorModule.SET_DISPLAY_DIALOG(false);
            // Receiving events for public channels :: begin
            this.$getConst("PUBLIC_CHANNELS").forEach((item: IPuhserChannels) => {
                window.Echo.channel(item.name).listen(item.event, (e: IObject) => {
                    if (this.$route.fullPath != "/users") {
                        if (e.operation == this.$getConst("ADD_OPERATION")) {
                            this.$store.commit(
                                `${item.storeName}/APPEND_${item.mutationSuffix}`,
                                e[item.responseName as string]
                            );
                        } else if (
                            e["operation"] == this.$getConst("EDIT_OPERATION")
                        ) {
                            this.$store.commit(
                                `${item.storeName}/UPDATE_${item.mutationSuffix}`,
                                e[item.responseName as string]
                            );
                        } else if (
                            e["operation"] == this.$getConst("REMOVE_OPERATION")
                        ) {
                            this.$store.commit(
                                `${item.storeName}/REMOVE_${item.mutationSuffix}`,
                                e[item.responseName as string]
                            );
                        } else if (
                            e["operation"] ==
                            this.$getConst("REMOVE_MULTIPLE_OPERATION")
                        ) {
                            this.$store.commit(
                                `${item.storeName}/REMOVE_SELECTED_${item.mutationSuffix}`,
                                e[item.responseName as string]
                            );
                        }
                    }
                });
            });
            // Receiving events for public channels :: end
        }
    }

    export default App;
</script>
