<template>
    <div>
        <div
            class="headline black-bg mb-4"
            primary-title
        >
        <span>
          {{ isEditMode ? $getConst('TXT_UPDATE') : $getConst('TXT_CREATE') }}
          Order
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
                                
                                v-model="model.quantity"
                                label="Quantity"
                                name="quantity"
                                
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
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                            
                        >
                            <v-text-field
                                
                                v-model="model.gst"
                                label="Gst"
                                name="gst"
                                
                                :error-messages="
                                    getErrorValue(
                                        'gst',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength=""
                                aria-label="Gst"
                            />
                        </v-flex>
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                            
                        >
                            <v-text-field
                                
                                v-model="model.payment_amount"
                                label="Payment Amount"
                                name="payment_amount"
                                
                                :error-messages="
                                    getErrorValue(
                                        'payment_amount',
                                        errors,
                                        validationMessages
                                    )
                                "
                                maxlength=""
                                aria-label="Payment Amount"
                            />
                        </v-flex>
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <label>Order Status*</label>
                            <v-radio-group
                                v-model="model.order_status"
                                v-validate="'required'"
                                row
                                name="order_status"
                                :error-messages="
                                    getErrorValue(
                                        'order_status',
                                        errors,
                                        validationMessages
                                    )
                                "
                                class="p-0 mt-1"
                                aria-label="Order Status"
                            >
                                
                                <v-radio
                                    label="Pending"
                                    value="0"
                                />
                                <v-radio
                                    label="Inprocess"
                                    value="1"
                                />
                                <v-radio
                                    label="Cancel"
                                    value="2"
                                />
                                <v-radio
                                    label="Completed"
                                    value="3"
                                />
                                <v-radio
                                    label="Return"
                                    value="4"
                                />
                            </v-radio-group>
                        </v-flex>
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                            
                        >
                            <v-textarea
                              
                              v-model="model.order_status_remark"
                              label="Order Status Remark"
                              name="order_status_remark"
                              
                              :error-messages="
                                    getErrorValue(
                                        'order_status_remark',
                                        errors,
                                        validationMessages
                                    )
                              "
                              maxlength=""
                              rows="3"
                              aria-label="Order Status Remark"
                            ></v-textarea>
                        </v-flex>
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                            
                        >
                            <v-textarea
                              
                              v-model="model.user_remark"
                              label="User Remark"
                              name="user_remark"
                              
                              :error-messages="
                                    getErrorValue(
                                        'user_remark',
                                        errors,
                                        validationMessages
                                    )
                              "
                              maxlength=""
                              rows="3"
                              aria-label="User Remark"
                            ></v-textarea>
                        </v-flex>
            
                        <v-flex
                            xs12
                            lg6
                            class="p-md-2"
                        >
                            <v-select
                                v-model="model.order_product|order_id|product_id"
                                
                                label="Order Product|order"
                                name="order_product|order_id|product_id"
                                :items="productList"
                                item-text="name"
                                item-value="id"
                                multiple
                                :loading="isDataLoading"
                                :error-messages="
                                    getErrorValue(
                                        'order_product|order_id|product_id',
                                        errors,
                                        validationMessages
                                    )
                                "
                                 aria-label="Order Product|order"
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
<script lang="ts" src="./add-edit-order.ts"></script>
