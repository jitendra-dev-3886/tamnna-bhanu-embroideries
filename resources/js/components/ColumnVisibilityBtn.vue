<template>
    <v-menu
        v-model="toggleSelect"
        :close-on-content-click="false"
        :nudge-width="200"
        offset-y
        aria-label="Column visibility menu"
    >
        <template v-slot:activator="{ on: onColMenu }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on: onTooltip, attrs }">
                    <v-btn
                        class="mb-2 mr-2"
                        color="indigo"
                        dark
                        v-bind="attrs"
                        v-on="{ ...onColMenu, ...onTooltip }"
                        aria-label="Column visibilty button"
                    >
                        <v-icon small>
                            {{ icons.mdiEye }}
                        </v-icon>
                    </v-btn>
                </template>
                <span>{{ $getConst("COLUMN_TOOLTIP") }}</span>
            </v-tooltip>
        </template>
        <v-card class="p-4">
            <v-list>
                <v-btn
                    text
                    class="float-right filter-close-btn"
                    @click="toggleSelect = false"
                    aria-label="Close button"
                >
                    <v-icon small>
                        {{ icons.mdiClose }}
                    </v-icon>
                </v-btn>
                <v-card-title> Columns </v-card-title>
                <v-divider></v-divider>
                <v-list dense class="columnVisibility">
                    <v-list-item v-for="item in headers" :key="item.value">
                        <v-checkbox
                            v-model="selectedHeaders"
                            :label="item.text"
                            :value="item"
                            :value-comparator="comparator"
                            :disabled="item.value == 'actions'"
                            @click.native.stop="updateHeaders"
                            aria-label="Column selection"
                        ></v-checkbox>
                    </v-list-item>
                </v-list>
            </v-list>
        </v-card>
    </v-menu>
</template>

<script lang="ts">
import CommonServices from "../mixins/common";
import { Component, Emit, Mixins, Prop } from "vue-property-decorator";
import { IObject } from "../../assets/types/common";
import { ITableHeaders } from "../../assets/types/table";

@Component
class ColumnVisibility extends Mixins(CommonServices) {
    @Prop({ default: false }) toggleSelect!: boolean;
    @Prop({ default: [] }) headers!: ITableHeaders[];
    @Prop({ default: [] }) selectedHeaders!: ITableHeaders[];

    comparator(a: IObject, b: IObject): boolean {
        return a.text == b.text;
    }

    @Emit()
    updateHeaders(): ITableHeaders[] {
        return this.selectedHeaders;
    }

    @Emit()
    closeMenu(e: IObject): IObject {
        return e;
    }
}
export default ColumnVisibility;
</script>
<style scoped>
.columnVisibility {
    height: 200px; /* or any height you want */
    overflow-y: auto;
}
</style>
