<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @click:outside="handleOk()"
        @keydown.esc="handleOk()"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                Error
            </v-card-title>

            <v-card-text>
                <form method="POST" name="" role="form">
                    <v-layout row wrap class="display-block m-0">
                        <v-flex v-if="Array.isArray(errorArr)" xs12>
                            <p v-for="(err, index) in errorArr" :key="index">
                                {{ err.name }} : {{ err.message }}
                            </p>
                        </v-flex>
                        <v-flex v-else xs12>
                            <p v-html="errorArr" />
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <v-btn
                                class="btn btn-black m-b-10 m-t-10"
                                @click.native="handleOk()"
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
import { ErrorModule } from "../store/error";
import { IObject } from "../../assets/types/common";

@Component
class ErrorModal extends Vue {
    @Prop({ default: false }) value!: boolean;
    @Prop({ default: () => [] }) errorArr!: string | [] | IObject[];

    handleOk(): void {
        this.$emit("input");
        ErrorModule.willDisplayDialog(false);
    }
}
export default ErrorModal;
</script>
