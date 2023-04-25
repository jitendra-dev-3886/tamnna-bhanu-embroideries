<template>
    <div>
        <div class="headline black-bg mb-4">
            <span>
                {{
                    isEditMode
                        ? $getConst("TXT_UPDATE")
                        : $getConst("TXT_CREATE")
                }}
                Admin User
            </span>
        </div>

        <div>
            <!--begin::Signup-->
            <div class="login-form login-signin">
                <!--begin::Form-->
                <v-form
                    class="form"
                    method="POST"
                    role="form"
                    novalidate
                    autocomplete="off"
                    @submit.prevent="onSubmit()"

                >
                    <ErrorBlockServer :error-message="errorMessage" />
                    <v-layout row wrap class="m-0">
                        <v-flex
                                xs12
                                lg6
                                class="p-md-2"

                            >
                                <v-text-field
                                    v-model="model.name"
                                    label="Name"
                                    name="name"
                                    v-validate="'required|max:191'"
                                    maxlength="191"
                                    :error-messages="getErrorValue('name', errors, validationMessages)"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                                lg6
                                class="p-md-2"
                            >
                                <v-select
                                    v-model="model.role_id"
                                    v-validate="'required'"
                                    label="Role"
                                    name="role_id"
                                    :items="roleList"
                                    item-text="name"
                                    item-value="id"
                                    :loading="isDataLoading"
                                    :error-messages="getErrorValue('role_id', errors, validationMessages)"

                                />
                            </v-flex>
                           <!-- <v-flex
                                xs12
                                lg6
                                class="p-md-2"

                            >
                                <v-text-field
                                    v-model="model.company_name"
                                    label="Company Name*"
                                    name="company_name"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                                lg6
                                class="p-md-2"

                            >
                                <v-text-field
                                    v-model="model.city"
                                    label="City*"
                                    name="city"
                                />
                            </v-flex>-->
                            <v-flex
                                xs12
                                lg6
                                class="p-md-2"

                            >
                                <v-text-field
                                    v-model="model.email"
                                    label="Email*"
                                    name="email"
                                    v-validate="'required|email|max:191'"

                                    :error-messages="getErrorValue('email', errors, validationMessages)"
                                />
                            </v-flex>

                            <v-flex
                                class="p-md-2"
                                lg6
                                v-show="!isEditMode"
                                xs12
                            >
                                <v-text-field
                                    :append-icon="showPassword ? 'visibility' : 'visibility_off'"

                                    :type="showPassword ? 'text' : 'password'"
                                    @click:append="showPassword = !showPassword"
                                    autocomplete="new-password"
                                    label="Password*"
                                    maxlength="15"
                                    name="password"
                                    v-model="model.password"
                                    v-validate="
                                    isEditMode
                                        ? ''
                                        : {
                                              required: 'required',
                                              min: 8,
                                              max: 15,
                                              regex: password_rules,

                                          }
                                "
                                    :error-messages="getErrorValue('password', errors, validationMessages)"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                                lg6
                                class="p-md-2"

                            >
                                <v-text-field
                                    v-model="model.contact_number"
                                    label="Mobile Number*"
                                    name="contact_number"
                                    v-validate="'min:10'"
                                    :error-messages="getErrorValue('contact_number', errors, validationMessages)"
                                />
                            </v-flex>
                    </v-layout>

                    <!--begin::Action-->
                    <div class="form-group d-flex flex-wrap flex-left">

                        <v-btn
                            aria-label="Submit"
                            class="btn btn-primary font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                            type="submit"
                            :loading="isSubmitting"
                            :disabled="isDataLoading"
                        >
                            {{ $getConst('BTN_SUBMIT') }}
                        </v-btn>
                        <v-btn
                            class="
                                btn btn-grey
                                font-weight-bold
                                px-9
                                py-4
                                my-3
                                font-size-3
                                mx-4
                            "
                            @click.prevent="onCancel()"
                            aria-label="Cancel"
                        >
                            {{ $getConst("BTN_CANCEL") }}
                        </v-btn>
                    </div>
                    <!--end::Action-->
                </v-form>
                <!--end::Form-->
            </div>
            <!--end::Signup-->
        </div>
    </div>
</template>
<script lang="ts" src="./add-edit-user.ts"></script>
