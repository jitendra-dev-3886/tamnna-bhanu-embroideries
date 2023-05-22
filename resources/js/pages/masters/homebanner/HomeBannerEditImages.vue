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
                <span>Edit Feature Image</span>
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

            <ErrorBlockServer :error-message="errorMessage" />

            <v-card-text>
                <a :href="model.featured_image" target="_blank"
                    ><img
                        :src="model.featured_image"
                        width="100%"
                        height="250"
                        class="mb-2"
                /></a>
                <div
                    class="table-responsive table table-cp scrollbar height22 pd-images-table"
                >
                    <table class="table">
                        <thead></thead>
                        <tbody>
                            <tr>
                                <td>
                                    <v-file-input
                                        id="featured_image"
                                        ref="featured_image"
                                        v-model="featured_image"
                                        v-validate="
                                            'required|ext:jpeg,png,jpg|size:5120'
                                        "
                                        attach
                                        name="featured_image"
                                        label="Feature Image*"
                                        accept="image/jpg, image/jpeg, image/png"
                                        :persistent-hint="true"
                                        hint="Extension: jpg, jpeg, png | Size: Maximum 1MB"
                                        counter="1"
                                        @click:clear="featured_image = ''"
                                        aria-label="Featured_image"
                                    />
                                </td>
                                <td>
                                    <v-flex xs12 sm2 lg2 class="pl-2">
                                        <v-btn
                                            color="primary"
                                            type="submit"
                                            class="btn btn-theme float-xs-none"
                                            :loading="addImagesLoading"
                                            :disabled="featured_image == ''"
                                            @click="uploadFeatureImg()"
                                            >Update
                                        </v-btn>
                                    </v-flex>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script src="./homebanner-edit-images.ts"></script>
