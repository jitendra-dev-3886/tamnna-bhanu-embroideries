<template>
    <span v-show="errorList.has(validationField)" class="help-block">
        <span v-for="item in validatonArray" :key="item.key">
            <span
                v-if="getErrorRule(validationField) == item.key"
                style="color: red"
                >{{ item.value }}</span
            >
        </span>
    </span>
</template>

<script lang="ts">
import CommonServices from "../mixins/common";
import { Component, Mixins, Prop } from "vue-property-decorator";
import { ErrorBag } from "vee-validate";
import { IValidationArray } from "../../assets/types/partials";

@Component
class ErrorBlock extends Mixins(CommonServices) {
    @Prop({ default: "" }) validationField!: string;
    @Prop({ default: [] }) errorList!: ErrorBag;
    @Prop({ default: [] }) validatonArray!: IValidationArray;
}
export default ErrorBlock;
</script>
