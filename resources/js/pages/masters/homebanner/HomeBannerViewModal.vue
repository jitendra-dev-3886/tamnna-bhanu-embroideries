<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog view-modal"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>View User</span>
                <v-spacer></v-spacer>
                <v-btn
                    color="#fff"
                    icon
                    @click="onCancel()"
                    aria-label="Close modal"
                >
                    <v-icon color="#fff">{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>

                        <tr>
                            <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Name:</td>
                            <td class="font-weight-regular font-size-h6-sm"> {{ model.name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Feature Image:</td>
                            <td>
                                <a :href="model.featured_image">
                                    <v-img :src="model.featured_image" v-if="model.featured_image" width="100" height="100">
                                    </v-img>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Status:</td>
                            <td class="font-weight-regular font-size-h6-sm" v-if="(model.banner_status)=='1'">Active</td>
                            <td class="font-weight-regular font-size-h6-sm" v-else>Inactive</td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./homebanner-view-modal.ts"></script>

