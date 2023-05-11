<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @input="$emit('input')"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                {{
                    confirmation.title
                        ? confirmation.title
                        : $getConst("DELETE_TITLE")
                }}
            </v-card-title>

            <v-card-text>
                <ErrorBlockServer :error-message="errorMessage" />
                <v-layout row wrap class="display-block m-0">
                    <v-flex xs12>
                        <p>
                            {{
                                confirmation.description
                                    ? confirmation.description
                                    : $getConst("WARNING")
                            }}
                        </p>
                    </v-flex>
                </v-layout>

                <v-layout row wrap class="display-block m-0">
                    <v-flex xs12>
                        <v-btn
                            class="btn btn-black m-b-10 m-t-10"
                            :loading="isSubmitting"
                            @click.native="deleteAction"
                            aria-label="Delete"
                        >
                            {{ $getConst("BTN_DELETE") }}
                        </v-btn>
                        <v-btn
                            class="btn btn-grey m-b-10 ml-3 m-t-10"
                            @click.native="onCancel"
                            aria-label="Cancel"
                        >
                            {{
                                confirmation.btnCancelText
                                    ? confirmation.btnCancelText
                                    : $getConst("BTN_CANCEL")
                            }}
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
import ErrorBlockServer from "./ErrorBlockServer.vue";
import { SnackbarModule } from "../store/snackbar";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { IConfirmationProps, IParamProps } from "../../assets/types/common";
import CommonServices from "../mixins/common";

@Component({
    components: {
        ErrorBlockServer,
    },
})
class DeleteModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    @Prop({
        default: {
            idProps: "",
            storeProps: "",
        },
    })
    paramProps!: IParamProps;
    @Prop({
        default: {
            title: "",
            description: "",
            btnCancelText: "",
            btnConfirmationText: "",
        },
    })
    confirmation!: IConfirmationProps;

    public errorMessage = "";

    deleteAction(): void {
        this.isSubmitting = true;
        this.$store
            .dispatch(
                `${this.paramProps.storeProps}/delete`,
                this.paramProps.idProps
            )
            .then(
                () => {
                    this.isSubmitting = false;
                    SnackbarModule.setMsg(this.$getConst("DELETE_ACTION"));
                    this.$emit("delete-success");
                    this.onCancel();
                },
                (error) => {
                    this.isSubmitting = false;
                    this.errorMessage = this.getAPIErrorMessage(error.response);
                }
            );
    }
    onCancel(): void {
        this.$emit("input");
        this.errorMessage = "";
    }
}

export default DeleteModal;
</script>
