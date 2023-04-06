<template>
    <div>
        <v-snackbar
            :value="snackbar1"
            :bottom="y === 'bottom'"
            :color="color"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :right="x === 'right'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'"
        >
            {{ msg }}
        </v-snackbar>
    </div>
</template>

<script lang="ts">
import { SnackbarModule } from "../store/snackbar";
import { Component, Vue, Watch } from "vue-property-decorator";

@Component({
    name: "Snackbar",
})
export default class Snackbar extends Vue {
    private color = "primary";
    private mode = "";
    private snackbar1: boolean = this.snackbar;
    private timeout = 6000;
    private x = "right";
    private y = "bottom";

    get msg(): string {
        return SnackbarModule.msg;
    }

    get snackbar(): boolean {
        return SnackbarModule.snackbar;
    }

    @Watch("snackbar")
    onChildChanged(val: boolean): void {
        this.snackbar1 = val;
        //To reset the message and snackbar model to false once shown
        setTimeout(() => {
            SnackbarModule.clearStore();
        }, this.timeout);
    }
}
</script>
