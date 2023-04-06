<template>
    <span>
        <v-select
            v-model="periodModel.period_type"
            name="period_type"
            item-text="name"
            item-value="enum"
            :items="periodTypeItems"
            label="Date Filter"
            @change="$emit('change')"
            class="mt-4"
        />
        <v-flex v-if="periodModel.period_type == '0'">
            <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                class="display-inline-blc"
            >
                <template v-slot:activator="{ on }">
                    <v-text-field
                        :value="
                            computedDateFormattedMomentjs(
                                periodModel.start_date
                            )
                        "
                        readonly
                        v-on="on"
                        label="Start Date*"
                        single-line
                        class="p-l-5"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="periodModel.start_date"
                    @change="$emit('change')"
                    @input="menu = false"
                ></v-date-picker>
            </v-menu>
        </v-flex>
        <v-flex v-if="periodModel.period_type == '0'">
            <v-menu
                v-model="menu2"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                class="display-inline-blc"
            >
                <template v-slot:activator="{ on }">
                    <v-text-field
                        :value="
                            computedDateFormattedMomentjs(periodModel.end_date)
                        "
                        readonly
                        v-on="on"
                        label="End Date*"
                        single-line
                        class="p-l-5"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="periodModel.end_date"
                    @change="$emit('change')"
                    @input="menu2 = false"
                    :min="periodModel.start_date"
                ></v-date-picker>
            </v-menu>
        </v-flex>
    </span>
</template>
<script lang="ts">
import CommonServices from "../mixins/common";
import { Component, Mixins, Prop, Watch } from "vue-property-decorator";
import { ActivityLogModule } from "../store/activity-log";
import { IObject } from "../../assets/types/common";
import { IPeriodTypeItems } from "../../assets/types/activity-log";
import { IFilterModel } from "../../assets/types/table";

@Component
class PeriodFilter extends Mixins(CommonServices) {
    @Prop({ default: {} }) value!: IFilterModel;

    periodModel: IFilterModel = this.value;
    menu = false;
    menu2 = false;

    get periodTypeItems(): IPeriodTypeItems[] {
        return ActivityLogModule.periodTypeItems;
    }

    @Watch("value")
    onValueChanged(): void {
        this.periodModel = this.value;
    }
}

export default PeriodFilter;
</script>
