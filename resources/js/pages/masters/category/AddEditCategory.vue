<template>
    <div>
        <div
            class="headline black-bg mb-4"
            primary-title
        >
        <span>
          {{ isEditMode ? $getConst('TXT_UPDATE') : $getConst('TXT_CREATE') }}
          Category
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

                        <v-flex
                            xs12
                            lg12
                            class="p-md-2"
                        >
                            <v-textarea
                                id="description"
                                label="Remarks"
                                v-model="model.description"
                                name="description"
                                ref="description"
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
                        </v-flex>

                        <v-flex
                            xs12
                            lg12
                            class="p-md-2"
                            v-show="!isEditMode"
                        >
                            <v-file-input
                                id="featured_image"
                                ref="featured_image"

                                v-model="model.featured_image"
                                v-validate="isEditMode ? 'ext:ext:jpeg,png,jpg,gif,svg|size:500' : 'required|ext:ext:jpeg,png,jpg,gif,svg|size:500'"
                                attach
                                counter="1"
                                :label="isEditMode ? 'Feature Image' : 'Feature Image*' "
                                name="featured_image"
                                accept="image/*"
                                hint="Maximum 500KB"
                                :error-messages="
                                    getErrorValue(
                                        'featured_image',
                                        errors,
                                        validationMessages
                                    )
                                "
                                @click:clear="model.featured_image='' "
                                aria-label="Featured_image"
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

                        >
                            {{ $getConst('BTN_SUBMIT') }}
                        </v-btn>
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
<script lang="ts" src="./add-edit-category.ts"></script>
