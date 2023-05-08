<template>
    <div class="w50 login-width">
        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">Sign In</h3>
                <p class="text-muted font-weight-semi-bold">
                    Enter your Username and password
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
                            v-model="loginDetail.email"
                            v-validate="'required'"
                            label="Username*"
                            type="text"
                            name="email"
                            autofocus
                            :error-messages="getErrorValue('email', errors, validationMessages)"
                            data-test-id="username"
                        />
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field
                            :append-icon="
                                showPassword ? 'visibility' : 'visibility_off'
                            "
                            v-model="loginDetail.password"
                            v-validate="'required'"
                            label="Password*"
                            :type="showPassword ? 'text' : 'password'"
                            @click:append="showPassword = !showPassword"
                            name="password"
                            data-test-id="password"
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
                    <v-layout>
                        <v-flex xs12>
                            <v-checkbox
                                class="
                                    text-dark-60 text-hover-primary
                                    float-left
                                "
                                v-model="remember_me"
                                data-test-id="rememberme-checkbox"
                                name="remember_me"
                                aria-label="Remember Me"
                                label="Remember Me"
                                style="color: #007190; margin-top: -5px"
                                true-value="1"
                                false-value="0"
                                @change="rememberMe()"
                            >
                            </v-checkbox>
                            <!-- </v-flex>
                        <v-flex xs6> -->
                         <!--   <a
                                id="kt_login_forgot"
                                class="
                                    text-dark-60 text-hover-primary
                                    my-3
                                    mr-2
                                    float-right
                                    mt-0
                                "
                                @click="forgotPasswordDialog = true"
                                data-test-id="loginpage-forgot-password"
                            >
                                Forgot Password?
                            </a>-->
                        </v-flex>
                    </v-layout>
                    <v-btn
                        ref="kt_login_signin_submit"
                        class="btn w100 btn-primary float-right mr-0 mt-5 font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                        type="submit"
                        data-test-id="submit-btn"
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
