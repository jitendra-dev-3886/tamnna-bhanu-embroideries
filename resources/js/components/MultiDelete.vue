<template>
    <span>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    :loading="loading"
                    min-width="48"
                    width="48"
                    color="teal darken-2"
                    dark
                    class="mb-2 mr-2"
                    v-on="on"
                    aria-label="Multiple Delete Button"
                >
                    <v-icon small>{{ icons.mdiDelete }}</v-icon>
                </v-btn>
            </template>
            <span>Multiple Delete</span>
        </v-tooltip>

        <v-dialog
            :value="deleteModal"
            content-class="modal-dialog"
            @input="onCancel"
            aria-label="Delete Dialog"
        >
            <v-card>
                <v-card-title class="headline black-bg" primary-title
                    >{{this.$getConst("DELETE_TITLE") }}
                </v-card-title>

                <v-card-text>
                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <p>
                                {{
                                    $getConst("MULTIPLE_DELETE_WARNING_START") +
                                    deleteProps.ids.length +
                                    $getConst("MULTIPLE_DELETE_WARNING_END")
                                }}
                            </p>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap class="display-block m-0">
                        <v-flex xs12>
                            <v-btn
                                class="btn btn-black m-b-10 m-t-10"
                                :loading="loading"
                                @click.native="deleteAction"
                                aria-label="Delete Button"
                                >{{ $getConst("BTN_DELETE") }}</v-btn
                            >
                            <v-btn
                                class="btn btn-grey m-b-10 ml-3 m-t-10"
                                @click.native="onCancel"
                                aria-label="Cancel"
                                >{{ $getConst("BTN_CANCEL") }}</v-btn
                            >
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </span>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from "vue-property-decorator";
import { IDeleteProps } from "../../assets/types/common";
import { SnackbarModule } from "../store/snackbar";
import CommonServices from "../mixins/common";

@Component
class MultiDelete extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    @Prop({
        default: {
            ids: "",
            store: "",
        },
    })
    deleteProps!: IDeleteProps;

    deleteModal = false;
    loading = false;

    deleteMulti(): void {
        this.deleteModal = true;
    }

    deleteAction(): void {
        this.loading = true;
        this.$store
            .dispatch(`${this.deleteProps.store}/multiDelete`, {
                id: this.deleteProps.ids,
            })
            .then(
                () => {
                    this.loading = false;
                    // if no error this code will execute
                    SnackbarModule.setMsg(this.$getConst("DELETE_ACTION"));
                    this.$emit("multiDelete");
                    this.onCancel();
                },
                (error) => {
                    this.loading = false;
                    this.showDialog(this.getAPIErrorMessage(error.response));
                }
            );
    }

    onCancel(): void {
        this.deleteModal = false;
        this.$emit("input");
    }
}

export default MultiDelete;
</script>
