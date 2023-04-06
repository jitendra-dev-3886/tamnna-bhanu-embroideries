<template>
    <v-alert
        v-if="errorMessage"
        dense
        outlined
        type="error"
        style="font-size: 13px"
        v-html="errorMessage"
    />
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";

@Component({
    name: "ErrorBlockServer",
})
export default class ErrorBlockServer extends Vue {
    @Prop({ default: "" }) errorMessage!: string;
}
</script>
