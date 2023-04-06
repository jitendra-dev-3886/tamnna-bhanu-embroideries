<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @click:outside="confirmationAction(false)"
        @keydown.esc="confirmationAction(false)"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                {{ $getConst("PERMISSION_DIALOG_TITLE") }}
            </v-card-title>

            <v-card-text>
                <form method="POST" name="" role="form">
                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <p>{{ $getConst("PERMISSION_DIALOG_MSG") }}</p>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <v-btn
                                class="btn btn-black m-b-10 m-t-10"
                                @click="confirmationAction(false)"
                            >
                                Ok
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import { PermissionModule } from "../store/permission";

@Component
class PermissionDialog extends Vue {
    @Prop({ default: false }) value!: boolean;

    confirmationAction(value: boolean): void {
        PermissionModule.SET_PERMISSION_DIALOGUE(value);
    }
}
export default PermissionDialog;
</script>
