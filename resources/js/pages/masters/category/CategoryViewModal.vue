<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                    class="headline black-bg mb-4" primary-title>
                <span>View Category</span>
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

                        <td><h4 class="font-weight-medium font-size-h6-sm" style="width: 50%;color: black;">Name:</h4></td>
                        <td><h4 class="font-weight-regular font-size-h6-sm" style="color:teal"> {{ model.name  != '' ? model.name  : '-' }}</h4></td>
                    </tr>

                    <tr>
                        <td><h4 class="font-weight-medium font-size-h6-sm" style="width: 50%;color: black;">Remarks:</h4></td>
                        <td><h4 class="font-weight-regular font-size-h6-sm"  v-if="model.description"><span style="color:teal" v-html="model.description"></span></h4></td>
                        <td><h4 class="font-weight-regular font-size-h6-sm" style="color:teal" v-if="!model.description"></h4>-</td>
                    </tr>

                    <tr>
                        <td><h4 class="font-weight-medium font-size-h6-sm" style="width: 50%;color: black;">Feature Image:</h4></td>
                        <td>
                                <a :href="model.featured_image">
                                    <v-img :src="model.featured_image" v-if="model.featured_image" width="100" height="100">
                                    </v-img>
                                </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./category-view-modal.ts"></script>
