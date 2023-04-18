<template>
    <v-dialog
        :value="value"
        content-class="modal-dialog"
        @click:outside="onCancel()"
        @keydown.esc="onCancel"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                Forgot Password
            </v-card-title>

            <v-card-text>
                <form
                    method="POST"
                    name="forgotPassword"
                    role="form"
                    @submit.prevent="onSubmit"
                >
                    <ErrorBlockServer :error-message="errorMessage" />
                    <v-text-field
                        v-model="email"
                        v-validate="'required|email'"
                        class="mt-4"
                        label="Enter your registered email *"
                        data-test-id="email"
                        :error-messages="
                            getErrorValue('email', errors, validationMessages)
                        "
                        name="email"
                        type="email"
                        maxlength="50"
                    />
                    <v-btn
                        class="btn btn-primary mt-4"
                        :loading="isSubmittingForgotPassword"
                        type="submit"
                        data-test-id="submit-btn"
                    >
                        {{ $getConst("BTN_SUBMIT") }}
                    </v-btn>
                    <v-btn
                        color=""
                        class="btn btn-grey ml-3 mt-4"
                        @click="onCancel()"
                        data-test-id="cancel-btn"
                    >
                        {{ $getConst("BTN_CANCEL") }}
                    </v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts" src="./forgot-password.ts"></script>
