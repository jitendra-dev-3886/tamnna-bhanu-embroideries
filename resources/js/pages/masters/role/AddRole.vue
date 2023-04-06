<template>
    <v-dialog
        :value="value"
        :persistent="loading"
        content-class="modal-dialog"
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title
                class="headline black-bg"
                primary-title
            >
                {{ isEditMode ? this.$getConst('TXT_UPDATE') : this.$getConst('TXT_ADD') }} Role
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="role-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="addAction"
                >
                    <error-block-server :error-message="errorMessage"/>
                    <v-layout
                        row
                        wrap
                        class="display-block m-0 "
                    >
                        <v-flex xs12>
                            <v-text-field
                                v-model="model.name"
                                v-validate="'required|max:191'"
                                label="Role*"
                                type="text"
                                name="role"
                                :error-messages="getErrorValue('role', errors, validationMessages)"
                                maxlength="191"
                            />
                        </v-flex>
                        <v-flex
                            xs12
                            class="mt-4"
                        >
                            <v-btn
                                class="btn btn-primary"
                                type="submit"
                                :loading="loading"
                            >
                                {{ $getConst('BTN_SUBMIT') }}
                            </v-btn>
                            <v-btn
                                class="btn btn-grey ml-3"
                                :disabled="loading"
                                @click.native="onCancel()"
                            >
                                {{ $getConst('BTN_CANCEL') }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts" src="./addrole.ts">
</script>
