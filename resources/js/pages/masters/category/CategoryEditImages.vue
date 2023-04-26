<template>
    <v-dialog
        persistent
        :value="value"
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
        content-class="modal-lg modal-dialog view-modal"
    >
    <v-card>
        <v-card-title class="headline black-bg mb-4" primary-title>
                <span>Edit Image</span>
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

        <div
            class="table-responsive table table-cp scrollbar height22 pd-images-table"
        >
        <table class="table">
            <thead>
                <tr>
                    <th>Featured Image</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <v-file-input
                            show-size
                            counter
                            multiple
                            label="File input"
                            @click:clear="model.featured_image='' "
                        >
                        </v-file-input>
                    </td>
                    <td>
                        <v-flex xs12 sm2 lg2 class="pl-2">
                            <v-btn
                                color="primary"
                                type="submit"
                                class="btn btn-theme float-xs-none"
                                :loading="addImagesLoading"
                                :disabled="isUploading"
                                >Upload Image
                            </v-btn>
                        </v-flex>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        <div
            class="table-responsive table table-cp scrollbar height22 pd-images-table"
        >
            <table class="table">
                <thead>
                    <tr class="no-border">
                        <th>Existing Featured Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a :href="model.featured_image" target="_blank"
                                ><img
                                    :src="model.featured_image"
                                    width="auto"
                                    height="150"
                                    class="mb-4"
                            /></a>
                        </td>
                        <td>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                        v-on="on"
                                        class="mr-2"
                                        @click="
                                            deleteItem(model.id)
                                        "

                                    >
                                        {{ icons.mdiDelete }}
                                    </v-icon>
                                </template>
                                <span>{{ $getConst("DELETE_TOOLTIP") }}</span>
                            </v-tooltip>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <v-btn tile color="primary" @click="onCancel()">
                                Cancel
                            </v-btn>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </v-card>
</v-dialog>
</template>

<script src="./category-edit-images.ts"></script>
