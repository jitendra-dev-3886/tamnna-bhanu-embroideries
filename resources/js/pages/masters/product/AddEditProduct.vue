<template>
    <div>
        <div
            class="headline black-bg mb-4"
            primary-title
        >
        <span>
          {{ isEditMode ? $getConst('TXT_UPDATE') : $getConst('TXT_CREATE') }}
          Product
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
                    enctype="multipart/form-data"
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
                            lg6
                            class="p-md-2"

                        >
                            <v-text-field

                                v-model="model.price"
                                label="Price*"
                                name="price"
                                v-validate="'required'"
                                :error-messages="
                                    getErrorValue(
                                        'price',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength=""
                                aria-label="Price"
                            />
                        </v-flex>


                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"

                        >
                            <v-text-field

                                v-model="model.item_code"
                                label="Item Code*"
                                name="item_code"
                                v-validate="'required|max:191'"
                                :error-messages="
                                    getErrorValue(
                                        'item_code',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength="191"
                                aria-label="Item Code"
                            />
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-select
                                v-model="model.category_id"
                                v-validate="'required'"
                                label="Category*"
                                name="category_id"
                                :items="categoryList"
                                item-text="name"
                                item-value="id"

                                :loading="isDataLoading"
                                :error-messages="
                                    getErrorValue(
                                        'category_id',
                                        errors,
                                        validationMessages
                                    )
                                "
                                 aria-label="Category"
                            />
                        </v-flex>

                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <label></label>
                            <v-radio-group
                                v-model="model.available_status"
                                label="Available Status*"
                                v-validate="'required'"
                                row
                                name="available_status"
                                :error-messages="
                                    getErrorValue(
                                        'available_status',
                                        errors,
                                        validationMessages
                                    )
                                "
                                class="p-0 mt-1"
                                aria-label="Available Status"
                            >

                                <v-radio
                                    label="Not-available"
                                    value="0"
                                />
                                <v-radio
                                    label="Available"
                                    value="1"
                                />
                            </v-radio-group>
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-file-input
                                id="featured_image"
                                ref="featured_image"
                                v-model="model.featured_image"
                                v-validate="isEditMode ? 'ext:ext:jpeg,png,jpg,gif,svg|size:500' : 'required|ext:ext:jpeg,png,jpg,gif,svg|size:500'"
                                attach
                                :label="isEditMode ? 'Featured Image' : 'Featured Image*' "
                                name="featured_image"
                                hint="Maximum 500KB"
                                :error-messages="
                                    getErrorValue(
                                        'featured_image',
                                        errors,
                                        validationMessages
                                    )
                                "
                                @click:clear="model.featured_image=''"
                                aria-label="Featured_image"
                            />
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"

                        >
                            <v-text-field

                                v-model="model.stock"
                                label="Stock"
                                name="stock"

                                :error-messages="
                                    getErrorValue(
                                        'stock',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength=""
                                aria-label="Stock"
                            />
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-file-input
                                v-model="model.product_galleries"
                                v-validate.continues="isEditMode ? 'ext:jpeg,png,jpg,gif,svg|size:4096|valid_file_length:5' : 'required|ext:jpeg,png,jpg,gif,svg|size:4096|valid_file_length:5'"
                                multiple
                                name="product_galleries"
                                hint="Maximum 4MB"
                                counter="5"
                                :error-messages="
                                    getErrorValue(
                                        'product_gallery|product_id|gallery',
                                        errors,
                                        validationMessages
                                    )
                                "
                                :label="isEditMode ? 'Product Galleries' : 'Product Galleries*' "
                                @click:clear="model.product_galleries=[]"
                                aria-label="Product Galleries"
                            />
                        </v-flex>
                        <v-flex
                            xs12
                            lg6
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
<script lang="ts" src="./add-edit-product.ts"></script>
