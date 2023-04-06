<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                    class="headline black-bg mb-4" primary-title>
                <span>View Product</span>
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
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Name:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.name  != '' ? model.name  : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Price:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.price  != '' ? model.price  : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Description:</td>
                        <td class="font-weight-regular font-size-h6-sm" v-if="model.description"><span v-html="model.description"></span></td>
                        <td class="font-weight-regular font-size-h6-sm" v-if="!model.description">-</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Item code:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.item_code  != '' ? model.item_code  : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Category:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.category ? model.category.name : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Available status:</td>
                        <td class="font-weight-regular font-size-h6-sm">{{ model.available_status_text != '' ? model.available_status_text : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Stock:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.stock  != '' ? model.stock  : '-' }}</td>
                    </tr>
            
                    <tr>
                        <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Featured image:</td>
                        <td class="font-weight-regular font-size-h6-sm"> {{ model.featured_image  != '' ? model.featured_image  : '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./product-view-modal.ts"></script>
