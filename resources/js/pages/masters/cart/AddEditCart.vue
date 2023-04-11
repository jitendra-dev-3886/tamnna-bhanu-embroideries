<template>
    <div>
        <div
            class="headline black-bg mb-4"
            primary-title
        >
        <span>
          {{ isEditMode ? $getConst('TXT_UPDATE') : $getConst('TXT_CREATE') }}
          Cart
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
                    <v-layout
                        row
                        wrap
                        class="m-0"
                    >

                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-select
                                v-model="model.user_id"
                                v-validate="'required'"
                                label="User*"
                                name="user_id"
                                :items="userList"
                                item-text="name"
                                item-value="id"

                                :loading="isBatchRequestLoading"
                                :error-messages="
                                    getErrorValue(
                                        'user_id',
                                        errors,
                                        validationMessages
                                    )
                                "
                                 aria-label="User"
                            />
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-select
                                v-model="model.product_id"
                                v-validate="'required'"
                                label="Product*"
                                name="product_id"
                                :items="productList"
                                item-text="name"
                                item-value="id"

                                :loading="isBatchRequestLoading"
                                :error-messages="
                                    getErrorValue(
                                        'product_id',
                                        errors,
                                        validationMessages
                                    )
                                "
                                 aria-label="Product"
                            />
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"

                        >
                            <v-text-field

                                v-model="model.quantity"
                                label="Quantity*"
                                name="quantity"
                                v-validate="'required'"
                                :error-messages="
                                    getErrorValue(
                                        'quantity',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength=""
                                aria-label="Quantity"
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
                            :disabled="isBatchRequestLoading"
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
<script lang="ts" src="./add-edit-cart.ts"></script>
