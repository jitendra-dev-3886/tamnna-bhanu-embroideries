<template>
    <div class="w50 login-width">
        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">Sign In</h3>
                <p class="text-muted font-weight-semi-bold">
                    Enter your Contact no and password
                </p>
            </div>

            <!--begin::Form-->
            <v-form
                class="form"
                novalidate
                autocomplete="off"
                @submit.prevent="onRecaptchaVerify"
            >
                <v-layout row wrap class="display-block m-0">
                    <ErrorBlockServer :error-message="errorMessage" />
                    <v-flex xs12>
                        <v-text-field
                            v-model="loginDetail.contact_number"
                            v-validate="'required'"
                            label="Contact No*"
                            type="text"
                            name="contact_number"
                            autofocus
                            :error-messages="
                                getErrorValue(
                                    'email',
                                    errors,
                                    validationMessages
                                )
                            "
                        />
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            v-model="loginDetail.password"
                            v-validate="'required|min:6'"
                            label="Password*"
                            type="password"
                            name="password"
                            :error-messages="
                                getErrorValue(
                                    'password',
                                    errors,
                                    validationMessages
                                )
                            "
                        />
                    </v-flex>
                </v-layout>

                <!--begin::Action-->
                <div class="form-group">
                    <v-btn
                        ref="kt_login_signin_submit"
                        class="btn w100 btn-primary float-right mr-0 mt-5 font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                        type="submit"
                        :loading="isSubmitting"
                    >
                        {{ $getConst("BTN_SUBMIT") }}
                    </v-btn>
                </div>
            </v-form>
            <!--end::Form-->
        </div>
        <!--end::Signin-->
        <forgot-password-modal v-model="forgotPasswordDialog" />
        <snackbar v-model="snackbar" />
        <permission-dialog v-model="permissionDialog" />
    </div>
</template>

<script lang="ts" src="./login.ts"></script>
