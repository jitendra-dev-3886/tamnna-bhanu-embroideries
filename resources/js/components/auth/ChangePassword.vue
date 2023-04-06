<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @input="onCancel"
        @keydown.esc="onCancel"
        @click:outside="onCancel"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                Change Password
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="changePassword"
                    role="form"
                    @submit.prevent="onSubmit"
                >
                    <ErrorBlockServer :error-message="errorMessage" />
                    <v-layout row wrap class="display-block m-0">
                        <v-flex lg12>
                            <v-text-field
                                id="old_password"
                                v-model="model.old_password"
                                v-validate="'required'"
                                :type="show_old_password ? 'text' : 'password'"
                                :append-icon="
                                    show_old_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="Current Password*"
                                class=""
                                name="old_password"
                                maxlength="50"
                                :error-messages="
                                    getErrorValue(
                                        'old_password',
                                        errors,
                                        validationMessages
                                    )
                                "
                                @click:append="
                                    show_old_password = !show_old_password
                                "
                            />
                        </v-flex>
                        <v-flex lg12>
                            <v-text-field
                                id="new_password"
                                ref="password"
                                v-model="model.new_password"
                                v-validate="'required|min:6'"
                                :type="show_new_password ? 'text' : 'password'"
                                :append-icon="
                                    show_new_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="New Password*"
                                class=""
                                name="new_password"
                                maxlength="50"
                                :error-messages="
                                    getErrorValue(
                                        'new_password',
                                        errors,
                                        validationMessages
                                    )
                                "
                                @click:append="
                                    show_new_password = !show_new_password
                                "
                            />
                        </v-flex>
                        <v-flex lg12>
                            <v-text-field
                                id="confirm_password"
                                v-model="model.confirm_password"
                                v-validate="'required|min:6|confirmed:password'"
                                :type="
                                    show_new_confirmation_password
                                        ? 'text'
                                        : 'password'
                                "
                                :append-icon="
                                    show_new_confirmation_password
                                        ? 'visibility'
                                        : 'visibility_off'
                                "
                                label="Confirm New Password*"
                                class=""
                                autocomplete="off"
                                name="confirm_password"
                                maxlength="50"
                                :error-messages="
                                    getErrorValue(
                                        'confirm_password',
                                        errors,
                                        validationMessages
                                    )
                                "
                                @click:append="
                                    show_new_confirmation_password =
                                        !show_new_confirmation_password
                                "
                            />
                        </v-flex>
                        <v-flex xs12>
                            <v-btn
                                class="btn btn-primary mt-4"
                                type="submit"
                                :loading="isSubmitting"
                            >
                                {{ $getConst("BTN_SUBMIT") }}
                            </v-btn>
                            <v-btn
                                class="btn btn-grey mt-4 ml-3"
                                @click="onCancel"
                            >
                                {{ $getConst("BTN_CANCEL") }}
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts" src="./change-password.ts"></script>
