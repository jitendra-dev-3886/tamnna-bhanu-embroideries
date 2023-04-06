<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                    class="headline black-bg mb-4" primary-title>
                <span>View Cart</span>
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
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">User:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.user ? model.user.name : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Product:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.product ? model.product.name : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Quantity:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.quantity  != '' ? model.quantity  : '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./cart-view-modal.ts"></script>
