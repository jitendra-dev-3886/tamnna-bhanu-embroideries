<template>
    <v-menu
        v-model="datetimeMenu"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        class="display-inline-blc"
        min-width="unset"
    >
        <template v-slot:activator="{ on }">
            <v-menu
                ref="menu"
                v-model="datetimeMenu"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                max-width="620px"
                min-width="620px"
                classs="p-3"
            >
                <template v-slot:activator="{ on }">
                    <v-text-field
                        :value="txtModel"
                        :label="label"
                        readonly
                        :name="name"
                        v-bind="
                            getErrorProps(name, errorType, validationMessages)
                        "
                        v-on="on"
                        :aria-label="`${label}`"
                    />
                </template>

                <div class="v-date-time-widget-container">
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-date-picker
                                v-model="datetimeDateModel"
                                width="280"
                                color="primary"
                            ></v-date-picker>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-time-picker
                                use-seconds="true"
                                v-if="datetimeMenu"
                                v-model="datetimeTimeModel"
                                color="primary"
                                ampm-in-title
                                format="ampm"
                                width="280"
                                class="datetime-picker"
                            ></v-time-picker>
                            <v-btn
                                small
                                fab
                                v-bind:class="[
                                    datetime_meridian == 'AM'
                                        ? 'primary'
                                        : 'darkgray',
                                ]"
                                class="btn-am mt-2"
                                @click="datetime_meridian = 'AM'"
                                >AM</v-btn
                            >                            
                            <v-btn
                                fab
                                small
                                v-bind:class="[
                                    datetime_meridian == 'PM'
                                        ? 'primary'
                                        : 'darkgray',
                                ]"
                                class="btn-pm mt-2"
                                @click="datetime_meridian = 'PM'"
                                >PM</v-btn
                            >
                        </v-flex>

                        <v-flex xs12 class="text-center m-4">
                            <h3 class="text-center mt-4 mb-3">
                                {{ currentSelection }}
                            </h3>

                            <v-btn
                                class="btn btn-primary"
                                small
                                @click="confirm()"
                                >Ok</v-btn
                            >
                            <v-btn
                                class="btn btn-grey"
                                small
                                @click="datetimeMenu = false"
                                >Cancel</v-btn
                            >
                        </v-flex>
                    </v-layout>
                </div>
            </v-menu>
        </template>
    </v-menu>
</template>

<script lang="ts">
import CommonServices from "../mixins/common";
import { Component, Mixins, Watch, Prop } from "vue-property-decorator";

@Component
class DateTimeField extends Mixins(CommonServices) {
    @Prop({ default: "" }) value!: string;
    @Prop({ default: "" }) name!: string;
    @Prop({ default: {} }) validationMessages!: unknown;
    @Prop({ default: "" }) label!: string;
    @Prop({ default: "error" }) errorType!:
        | "error"
        | "error-messages"
        | "errorMessages";

    datetimeMenu = false;
    displayDatetime = "";

    search = "";
    txtModel: string = this.value;

    dateRes = this.defaultSelectedDate();
    datetime_meridian = this.dateRes ? this.dateRes.meridian : "AM";
    datetimeTimeModel = this.dateRes ? this.dateRes.timeModel : "";
    datetimeDateModel = this.dateRes ? this.dateRes.dateModel : "";

    @Watch("value")
    onValueChanged(): void {
        this.txtModel = this.value;
        const [datetime_date, datetime_time, datetime_meridian] =
            this.txtModel.split(" ");
        this.datetimeTimeModel = this.formatTime(datetime_time);
        this.datetime_meridian = datetime_meridian;      
        this.datetimeDateModel = this.getCustomDateFormat(
            datetime_date
        ) as string;        
    }

    // Get the current selection of date-time value
    get currentSelection() {
        let selectedTime =
            this.datetimeTimeModel + " " + this.datetime_meridian;
        return this.getDateFormat(this.datetimeDateModel) + " " + selectedTime;
    }

    // Confirm the datetime selection and close the popover
    confirm() {
        this.onUpdateDate();
        this.datetimeMenu = false;
    }

    // Format the date and trigger the input event
    onUpdateDate() {
        if (!this.datetimeDateModel || !this.datetimeTimeModel) return false;

        let selectedTime =
            this.datetimeTimeModel + " " + this.datetime_meridian;
        this.txtModel =
            this.getDateFormat(this.datetimeDateModel) + " " + selectedTime;
           
        this.$emit("input", this.datetimeDateModel + " " + selectedTime);
    }
}
export default DateTimeField;
</script>
