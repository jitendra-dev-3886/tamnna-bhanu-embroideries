<template>
    <v-dialog
        :retain-focus="false"
        :value="value"
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
                <template>
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th
                                    v-for="(
                                            value, propertyName
                                        ) in tableData[0]"
                                    :key="propertyName"
                                    v-show="
                                            checkToShowPropertyOrNot(
                                                value,
                                                propertyName
                                            )
                                        "
                                    class="text-left"
                                >
                                    {{ propertyName | titleCase }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="(rowItem, index) in tableData"
                                :key="`${index}` + 'row'"
                            >
                                <template v-for="(val, rowKey) in rowItem">
                                    <td
                                        :key="val.id"
                                        v-if="
                                                Array.isArray(val) &&
                                                checkToShowPropertyOrNot(
                                                    val,
                                                    rowKey
                                                )
                                            "
                                    >
                                        <template
                                            v-for="(arrItem, index) in val"
                                        >
                                            <template
                                                v-if="
                                                        arrItem ===
                                                        Object(arrItem)
                                                    "
                                            >
                                                <v-tooltip
                                                    :key="'arrItem' + index"
                                                    bottom
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                                on,
                                                            }"
                                                    >
                                                        <v-icon
                                                            small
                                                            class="mr-2"
                                                            v-on="on"
                                                            @click="
                                                                    openTableModal(
                                                                        val,
                                                                        rowKey
                                                                    )
                                                                "
                                                        >
                                                            {{
                                                            icons.mdiEye
                                                            }}
                                                        </v-icon>
                                                    </template>
                                                    <span>{{
                                                            $getConst(
                                                                "VIEW_DETAILS_TOOLTIP"
                                                            )
                                                        }}</span>
                                                </v-tooltip>
                                            </template>
                                            <div
                                                v-else
                                                :key="'arrItem' + index"
                                            >
                                                {{ arrItem }}
                                            </div>
                                            <br
                                                :key="'lineBreak' + index"
                                            />
                                        </template>
                                    </td>
                                    <td
                                        v-else-if="val === Object(val)"
                                        :key="val.id"
                                    >
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on }"
                                            >
                                                <v-icon
                                                    small
                                                    class="mr-2"
                                                    v-on="on"
                                                    @click="
                                                            openObjectModal(
                                                                val,
                                                                prop
                                                            )
                                                        "
                                                >
                                                    {{ icons.mdiEye }}
                                                </v-icon>
                                            </template>
                                            <span>{{
                                                    $getConst(
                                                        "VIEW_DETAILS_TOOLTIP"
                                                    )
                                                }}</span>
                                        </v-tooltip>
                                        <object-modal
                                            :key="`${prop}innObj`"
                                            v-model="objectModalView"
                                        ></object-modal>
                                    </td>
                                    <td v-else :key="val.id">
                                        {{ val }}
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </template>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts">
    import CommonServices from "../../../mixins/common";
    import { Component, Mixins, Prop } from "vue-property-decorator";
    import { ActivityLogModule } from "../../../store/activity-log";
    import { IObject } from "../../../../assets/types/common";
    // import ObjectModal from '../activity-log/ObjectModal'
    @Component
    class TableModal extends Mixins(CommonServices) {
        @Prop({ default: false }) value!: boolean;

        objectModalView = false;
        tableModalView = false;

        get tableData(): IObject[] {
            return ActivityLogModule.tableModalData.tableData;
        }
        get col(): string {
            return ActivityLogModule.tableModalData.col;
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
        checkToShowPropertyOrNot(value: IObject[], prop: string): boolean {
            return !(
                prop.indexOf &&
                (prop.indexOf("_id") !== -1 ||
                    prop == "created_at" ||
                    prop == "updated_at" ||
                    prop == "deleted_at" ||
                    prop == "created_by" ||
                    prop == "updated_by" ||
                    prop == "pivot")
            );
        }
    }
    export default TableModal;
</script>
<style scoped>
    .theme--light.v-data-table > .v-data-table__wrapper > table > thead > tr > th {
        color: #000 !important;
    }
</style>
