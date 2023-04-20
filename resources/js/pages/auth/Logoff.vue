<template>
    <div class="w50 login-width">
        <!--begin::Signin-->
        <div class="login-form login-signin">
            <div class="text-center mb-10 mb-lg-20">
                <h3 class="font-size-h1">
                    Log In
                </h3>
                <p class="text-muted font-weight-semi-bold">
                    {{ $getConst('LOGGOFF_TEXT') }}
                </p>
            </div>

            <!--begin::Form-->
            <v-form
                class="form"
                novalidate
                autocomplete="off"
                @submit.prevent="onRecaptchaVerify"
            >
                <div
                    role="alert"
                    class="alert fade alert-danger"
                >
                    <div class="alert-text">
                        {{ errorMessage }}
                    </div>
                </div>

                <v-layout
                    row
                    wrap
                    class="display-block m-0"
                >
                    <div class="text-center">
                        <h3 class="logoff-user-name mt-3">
                            {{ userFullName }}
                        </h3>
                        <v-text-field
                            v-model="loginDetail.password"
                            v-validate="'required'"
                            label="Password*"
                            type="password"
                            name="password"
                            :error-messages="getErrorValue('password', errors, validationMessages)"
                            :type="showPassword ? 'text' : 'password'"
                            :append-icon="
                                showPassword ? 'visibility' : 'visibility_off'
                            "
                            @click:append="showPassword = !showPassword"
                        />
                    </div>
                </v-layout>

                <!--begin::Action-->
                <div class="form-group d-flex flex-wrap flex-center">
                    <v-layout>
                        <v-flex xs12>
                            <a
                                id="kt_login_forgot"
                                class="text-dark-60 text-hover-primary
                                my-3 mr-2 float-right mt-0"
                                @click="logout()"
                            >
                            Logout?
                            </a>
                        </v-flex>
                    </v-layout>
                    <v-btn
                        ref="kt_login_signin_submit"
                        class="btn btn-primary w100 font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                        type="submit"
                        :loading="isSubmitting"
                    >
                        {{ $getConst('BTN_CONTINUE') }}
                    </v-btn>
                </div>
                <!--end::Action-->
            </v-form>
            <!--end::Form-->
        </div>
        <!--end::Signin-->
        <snackbar v-model="snackbar"/>
    </div>
</template>

<style lang="scss" scoped>
.spinner.spinner-right {
    padding-right: 3.5rem !important;
}
</style>

<script lang="ts" src="./logoff.ts"></script>
