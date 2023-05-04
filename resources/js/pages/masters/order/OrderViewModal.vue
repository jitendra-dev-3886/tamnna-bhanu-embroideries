<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
    <div class="order-detail">
    <v-card>
            <v-card-text>
                <v-layout class="fs">
                    <v-flex xs12 sm12 md12 lg12>
                        <v-spacer></v-spacer>
                        <h3 class="mb-4">Order id #{{ model.id }}</h3>
                    </v-flex>
                </v-layout>
                <form
                    method="POST"
                    name="order-status-form"
                    role="form"
                    novalidate
                    autocomplete="off"
                >
                    <v-layout class="fs">
                       <table>
                        <tr>

                           <td><h2 style="color: teal;">Order Status </h2></td>

                            <td><v-tab></v-tab></td>

                        <td><v-chip
                            class="ma-2"
                            color="success"
                            variant="outlined"
                            label
                            >
                        {{ model.order_status_text}}</v-chip></td>
                    </tr>
                    </table>
                    </v-layout>
                </form>
                <v-divider></v-divider>
                <template v-for="(order_products,index) in model.order_products">
                    <v-layout class="fs mt-7 mb-8" :key="index">
                        <table>
                        <td>
                            <img
                                :src="model.order_products[index].featured_image"
                                class="img-responsive"
                                width="80px"
                                height="80px"
                               style="margin-top: 20px;"
                            />
                        </td>
                        <td>
                            <v-tab></v-tab>
                        </td>
                        <td>
                            <h6 style="color: teal; margin-top: 20px;"><span style="color: black;padding-right: 20px;">Product Name:</span>{{ model.order_products[index].product_name }}</h6>
                            <h6 style="color: teal;"><span style="color: black;padding-right: 20px;">Category Name:</span>{{ model.order_products[index].category_name }}</h6>
                            <h6 style="color: teal;"><span style="color: black;padding-right: 20px;">Quantity:</span>{{ model.order_products[index].quantity}}</h6>
                            <h6 style="color: teal;"><span style="color: black;padding-right: 20px;">Price:</span>{{ model.order_products[index].price}}</h6>
                        </td>

                    </table>
                    </v-layout>
                </template>
                <v-layout class="fs mt-7 mb-8">
                    <table>
                        <v-divider></v-divider>
                        <tr>

                            <td>
                                <h6 style="color:black; margin-top: 20px;">GST:</h6>
                            </td>

                            <td>
                                <h6>
                                    <span style="color:teal;padding-left:20px;padding-top:20px;">{{parseFloat(model.gst).toFixed(2)}}</span>
                                </h6>
                            </td>
                        </tr>
                        <tr>

                            <td><h2 style="color: teal;">Total Amount </h2></td>



                            <td style="color:teal;padding-left:20px;"><v-chip
                            class="ma-2"
                            color="success"
                            variant="outlined"
                            label
                            >
                            {{parseFloat(model.payment_amount).toFixed(2)}}</v-chip></td>
                        </tr>
                    </table>

                </v-layout>
                <v-flex xs12 sm12 md12 lg12>
                        <v-btn class="btn btn-primary" @click=onCancel()>
                            Back

                        </v-btn>
                    </v-flex>
            </v-card-text>
        </v-card>
    </div>
    </v-dialog>
</template>
<script lang="ts" src="./order-view-modal.ts"></script>
