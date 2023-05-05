<template>
    <div>
        <div
            class="headline black-bg mb-4"
            primary-title
        >
        <span>
          {{ isEditMode ? $getConst('TXT_UPDATE') : $getConst('TXT_CREATE') }}
          Home Banner
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
                    enctype="multipart/form-data"
                    @submit.prevent="onSubmit()"

                >
                    <ErrorBlockServer :error-message="errorMessage" />
                    <v-layout
                        row
                        wrap
                        class="m-0"
                    >

                        <v-flex
                            xs12
                            lg12
                            class="p-md-2"

                        >
                            <v-text-field

                                v-model="model.name"
                                label="Name*"
                                name="name"
                                v-validate="'required|max:191'"
                                :error-messages="
                                    getErrorValue(
                                        'name',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength="191"
                                aria-label="Name"
                            />
                        </v-flex>

                        <!--<v-flex
                            xs12
                            lg12
                            class="p-md-2"
                        >
                            <v-textarea
                                id="description"
                                label="Remarks*"
                                v-model="model.description"
                                name="description"
                                ref="description"
                                v-validate="'required'"
                                auto-grow
                                outlined
                                :error-messages="
                                    getErrorValue(
                                        'description',
                                        errors,
                                        validationMessages
                                    )
                                "
                                ></v-textarea>
                            <ErrorBlock
                                validationField="description"
                                :errorList="errors"
                                :validatonArray="validationMessages.description"
                            ></ErrorBlock>
                        </v-flex>-->
                        <v-flex
                            xs12
                            lg12
                            class="p-md-2"
                        >
                       <v-radio-group
                                v-model="model.banner_status"
                                label="Banner Status*"
                                v-validate="'required'"
                                row
                                name="banner_status"
                                :error-messages="
                                    getErrorValue(
                                        'banner_status',
                                        errors,
                                        validationMessages
                                    )
                                "
                                class="p-0 mt-1"
                                aria-label="Banner Status"
                            >

                                <v-radio
                                    label="Inactive"
                                    value="0"

                                />
                                <v-radio
                                    label="Active"
                                    value="1"
                                />
                            </v-radio-group>
                        </v-flex>
                        <v-flex
                            xs12
                            lg12
                            v-show="!isEditMode"
                            class="p-md-2"
                        >
                            <v-file-input
                                id="featured_image"
                                ref="featured_image"
                                v-model="model.featured_image"
                                attach
                                counter="1"
                                accept="image/*"
                                :label="isEditMode ? 'Feature Image' : 'Feature Image*' "
                                name="featured_image"
                                hint="Maximum 500KB"
                                @click:clear="model.featured_image='' "
                                aria-label="Featured_image"

                            />
                        </v-flex>
                    </v-layout>

                    <!--begin::Action-->
                    <div class="form-group d-flex flex-wrap flex-left">

                        <!--<v-btn
                            aria-label="Submit"
                            :loading="isSubmitting"
                            class="btn btn-primary font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                            type="submit"

                        >
                            {{ $getConst('BTN_SUBMIT') }}
                        </v-btn>
                        -->
                        <v-btn class="btn btn-primary font-weight-bold px-9 py-4 my-3 font-size-3 mx-4" v-show="isEditMode" :loading="isSubmitting" @click.prevent="EditDetail()">{{ $getConst('TXT_UPDATE') }}</v-btn>
                        <v-btn class="btn btn-primary font-weight-bold px-9 py-4 my-3 font-size-3 mx-4" v-show="!isEditMode" :loading="isSubmitting" type="submit">{{ $getConst('BTN_SUBMIT') }}</v-btn>
                        <v-btn
                            class="btn btn-grey font-weight-bold px-9 py-4 my-3 font-size-3 mx-4"
                            @click.prevent="onCancel()"
                        >
                            {{ $getConst('BTN_CANCEL') }}
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
<script lang="ts" src="./add-edit-homebanner.ts"></script>
