<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                    class="headline black-bg mb-4" primary-title>
                <span>View Order</span>
                <v-spacer></v-spacer>
                <v-btn
                    icon
                    dark
                    @click="onCancel"
                >
                    <v-icon>{{icons.mdiClose}}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Quantity:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.quantity  != '' ? model.quantity  : '-' }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Gst:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.gst  != '' ? parseFloat(model.gst).toFixed(2)  : '-' }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Payment amount:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.payment_amount  != '' ? parseFloat(model.payment_amount).toFixed(2)  : '-' }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Order status:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.order_status_text != '' ? model.order_status_text : '-' }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Order status remark:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.order_status_remark  != '' ? model.order_status_remark  : '-' }}</td>
                    </tr>

                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">User remark:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.user_remark  != '' ? model.user_remark  : '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./order-view-modal.ts"></script>
