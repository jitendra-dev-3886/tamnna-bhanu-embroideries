<!--<template>
    <v-dialog :value="value" content-class="modal-lg modal-dialog" scrollable @click:outside="onCancel()"
              @keydown.esc="onCancel()">
        <v-card>
            <v-card-title
                    class="headline black-bg mb-4" primary-title>
                <span>View HomeBanner</span>
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
                            <td class="font-weight-regular font-size-h6-sm"> {{ model.name!=''? model.name:'-' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Description:</td>
                            <td class="font-weight-regular font-size-h6-sm" v-if="model.banner_status"><span v-html="model.banner_status"></span></td>
                            <td class="font-weight-regular font-size-h6-sm" v-if="!model.banner_status">-</td>
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
<script lang="ts" src="./homebanner-view-modal.ts"></script>-->

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
                            <td class="font-weight-medium font-size-h6-sm" style="width: 30%">Featured Image:</td>
                            <td class="font-weight-regular font-size-h6-sm"> {{ model.featured_image }}</td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./homebanner-view-modal.ts"></script>

