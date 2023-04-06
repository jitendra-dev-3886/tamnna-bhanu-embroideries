<template>
    <v-dialog
        :value="value"
        :retain-focus="false"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>View {{ col }}</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <!-- Created/Deleted View Start -->
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                    <tr
                        v-for="(value, propertyName) in modelData"
                        :key="propertyName"
                        v-show="
                                checkToShowPropertyOrNot(value, propertyName)
                            "
                    >
                        <td
                            class="font-weight-medium font-size-h6-sm"
                            style="width: 30%"
                        >
                            {{ propertyName | titleCase }}:
                        </td>
                        <td
                            class="font-weight-regular font-size-h6-sm"
                            v-if="Array.isArray(value)"
                        >
                            <template v-if="value[0] === Object(value[0])">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            small
                                            class="mr-2"
                                            v-on="on"
                                            @click="
                                                    openTableModal(
                                                        value,
                                                        propertyName
                                                    )
                                                "
                                        >
                                            {{ icons.mdiEye }}
                                        </v-icon>
                                    </template>
                                    <span>{{
                                            $getConst("VIEW_DETAILS_TOOLTIP")
                                        }}</span>
                                </v-tooltip>
                                <table-modal
                                    :key="`${propertyName}` + 'inTable'"
                                    v-model="tableModalView"
                                    :tableData="value"
                                ></table-modal>
                            </template>
                            <template v-else>
                                    <span
                                        v-for="(item, index) in value"
                                        :key="index + item"
                                    >
                                        {{ item }}
                                        <template
                                            v-if="index + 1 < value.length"
                                        >,</template
                                        >
                                    </span>
                            </template>
                        </td>
                        <td
                            class="font-weight-regular font-size-h6-sm"
                            v-else-if="value === Object(value)"
                        >
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        v-on="on"
                                        @click="
                                                openObjectModal(
                                                    value,
                                                    propertyName
                                                )
                                            "
                                    >
                                        {{ icons.mdiEye }}
                                    </v-icon>
                                </template>
                                <span>{{
                                        $getConst("VIEW_DETAILS_TOOLTIP")
                                    }}</span>
                            </v-tooltip>
                            <object-modal
                                :key="`${prop} ` + 'innObj'"
                                v-model="objectModalView"
                            ></object-modal>
                        </td>
                        <td v-else>
                            {{ value }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts">
    import CommonServices from "@/mixins/common";
    import { Component, Prop, Mixins } from "vue-property-decorator";
    import { ActivityLogModule } from "@/store/activity-log";
    import { IObject } from "../../../../assets/types/common";
    // import TableModal from "../activity-log/TableModal";

    @Component
    class ObjectModal extends Mixins(CommonServices) {
        @Prop({ default: false }) value!: boolean;
        tableModalView = false;
        objectModalView = false;

        get modelData(): IObject {
            return ActivityLogModule.objectModalData.modelData;
        }
        get col(): string {
            return ActivityLogModule.objectModalData.col;
        }
        openTableModal(val: IObject[], prop: string): void {
            ActivityLogModule.SET_TABLE_MODAL_DATA({
                tableData: val,
                col: prop,
            });
            this.tableModalView = true;
        }
        openObjectModal(val: IObject, prop: string): void {
            ActivityLogModule.SET_OBJECT_MODAL_DATA({
                modelData: val,
                col: prop,
            });
            this.objectModalView = true;
        }
        onCancel(): void {
            this.$emit("input"); //Close Pop-up
        }
        checkToShowPropertyOrNot(value: IObject, prop: string): boolean {
            return !(
                prop.indexOf("_id") !== -1 ||
                prop == "created_at" ||
                prop == "updated_at" ||
                prop == "deleted_at" ||
                prop == "created_by" ||
                prop == "updated_by" ||
                prop == "pivot"
            );
        }
    }
    export default ObjectModal;
</script>
