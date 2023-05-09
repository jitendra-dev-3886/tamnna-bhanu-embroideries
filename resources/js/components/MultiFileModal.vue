<template>
    <v-dialog
        :value="fileModal"
        @click:outside="onCancel()"
        @keydown.esc="onCancel"
        content-class="modal-lg modal-dialog view-modal"
    >
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                {{ variableMutation }}
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <ErrorBlockServer :error-message="errorMessage" />
                <v-layout class="display-block m-0" row wrap>
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
                                            id="featured_image"
                                            ref="featured_image"
                                            v-model="featured_image"
                                            v-validate="
                                                'required|ext:jpeg,png,jpg|size:1024'
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
                                                :loading="isProdFISubmitting"
                                                @click="uploadProdGallery(true)"
                                                class="btn btn-theme float-xs-none"
                                                >Upload
                                            </v-btn>
                                        </v-flex>
                                    </td>
                                </tr>
                                <template v-if="model.featured_image">
                                    <thead>
                                        <tr>
                                            <th>Existing Featured Image</th>
                                        </tr>
                                    </thead>
                                    <tr v-if="model.featured_image">
                                        <td>
                                            <a
                                                :href="model.featured_image"
                                                target="_blank"
                                                ><img
                                                    :src="model.featured_image"
                                                    width="auto"
                                                    height="100px"
                                                    class="mb-4"
                                                />
                                            </a>
                                        </td>
                                        <td style="width: 35%">
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-icon
                                                        @click="
                                                            confirmDelete(
                                                                model.id,
                                                                '',
                                                                true
                                                            )
                                                        "
                                                        class="mr-2"
                                                        small
                                                        v-on="on"
                                                        aria-label="Delete image"
                                                    >
                                                        {{ icons.mdiDelete }}
                                                    </v-icon>
                                                </template>
                                                <span>{{
                                                    $getConst("DELETE_TOOLTIP")
                                                }}</span>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                </template>

                                <thead>
                                    <tr>
                                        <th>Product Gallery Images</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>
                                        <v-file-input
                                            v-model="product_galleries"
                                            v-validate.continues="
                                                'ext:jpeg,png,jpg|size:5120|valid_file_length:5'
                                            "
                                            multiple
                                            name="product_galleries"
                                            accept="image/jpg, image/jpeg, image/png"
                                            :persistent-hint="true"
                                            hint="Extension: jpg, jpeg, png | Size: Maximum 5MB"
                                            counter="5"
                                            label=" Product Galleries
                                "
                                            @click:clear="
                                                product_galleries = []
                                            "
                                            aria-label="Product Galleries"
                                        />
                                    </td>
                                    <td>
                                        <v-flex xs12 sm2 lg2 class="pl-2">
                                            <v-btn
                                                color="primary"
                                                type="submit"
                                                :loading="isProdGalSubmitting"
                                                @click="
                                                    uploadProdGallery(false)
                                                "
                                                class="btn btn-theme float-xs-none"
                                                >Upload
                                            </v-btn>
                                        </v-flex>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <v-flex xs12>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Existing Gallery Images</th>
                                </tr>

                                <tr class="no-border">
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="fileArr">
                                <template v-if="fileArr.length != 0">
                                    <tr
                                        :key="singleFile.id"
                                        v-for="(singleFile, index) in fileArr"
                                    >
                                        <td>
                                            <a
                                                aria-label="File"
                                                :href="singleFile[filePath]"
                                                target="_blank"
                                            >
                                                <img
                                                    :src="
                                                        singleFile[
                                                            filePath +
                                                                '_thumbnail'
                                                        ]
                                                    "
                                                    height="100px"
                                                    v-if="
                                                        checkIfImage(
                                                            singleFile[filePath]
                                                        )
                                                    "
                                                    alt="View image in new tab"
                                                    aria-label="View image in new tab"
                                                    title="Thumbnail"
                                                />
                                                <p
                                                    :class="{
                                                        'mt-2': checkIfImage(
                                                            singleFile[filePath]
                                                        ),
                                                    }"
                                                >
                                                    {{
                                                        singleFile[
                                                            filePath +
                                                                "_original"
                                                        ]
                                                    }}
                                                </p>
                                            </a>
                                        </td>
                                        <td style="width: 25%">
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-icon
                                                        @click="
                                                            confirmDelete(
                                                                singleFile.id,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="mr-2"
                                                        small
                                                        v-on="on"
                                                        aria-label="Delete image"
                                                    >
                                                        {{ icons.mdiDelete }}
                                                    </v-icon>
                                                </template>
                                                <span>{{
                                                    $getConst("DELETE_TOOLTIP")
                                                }}</span>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="2" style="text-align: center">
                                        No Data available
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
        <delete-confirm
            :param-props="paramProps"
            @delete="deleteGallery"
            v-model="deleteConfirm"
        />
    </v-dialog>
</template>

<script lang="ts">
import CommonServices from "../mixins/common";
import ErrorBlockServer from "./ErrorBlockServer.vue";
import DeleteConfirm from "./DeleteConfirm.vue";
import { Component, Mixins, Prop } from "vue-property-decorator";
import { SnackbarModule } from "../store/snackbar";
import {
    IObject,
    ResponseResult,
    IParamProps,
} from "../../assets/types/common";
import { AxiosResponse } from "axios";
import { ProductModule } from "../store/product";
import {
    IProductFullResponse,
    IProductGalleries,
} from "../../assets/types/product";

@Component({
    components: {
        ErrorBlockServer,
        DeleteConfirm,
    },
})
class MultiFileModal extends Mixins(CommonServices) {
    @Prop({ default: () => [] }) fileArr!: IProductGalleries[];
    @Prop({ default: "" }) storeName!: string;
    @Prop({ default: "" }) variableMutation!: string;
    @Prop({ default: "" }) filePath!: string;
    @Prop({ default: false }) value!: boolean;

    email = "";
    deleteConfirm = false;
    isProdGalSubmitting = false;
    isProdFISubmitting = false;
    featured_image: Blob | string = "";
    product_galleries: Blob[] = [];

    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    fileModal = false;
    deleting = false;
    isFeatureImage: boolean = false;

    get model(): IProductFullResponse {
        return ProductModule.viewModel;
    }

    /**
     * Open Modal Box
     */
    manageFileModal(): void {
        this.fileModal = true;
    }

    /**
     * Cancel Method
     */
    onCancel(): void {
        this.fileModal = false;
    }

    /* Delete Modal  */
    confirmDelete(
        id: string | number,
        index: number | string,
        isFeatureImg: boolean
    ): void {
        this.paramProps.idProps = id;
        this.paramProps.indexProps = index; // when index will be "" that means its for feature image delete confirmation
        this.deleteConfirm = true;
        this.isFeatureImage = isFeatureImg;
    }

    /* Delete Image */
    deleteGallery(payload: IParamProps): void {
        this.deleteConfirm = false;
        this.deleting = true;
        let apiName = this.isFeatureImage
            ? "deleteFeatureImg"
            : "deleteGallery";

        ProductModule[apiName](payload.idProps as string).then(
            (response: AxiosResponse<ResponseResult<boolean>>) => {
                this.deleting = false;
                if (this.isFeatureImage) {
                    this.model.featured_image = "";
                } else {
                    this.fileArr.splice(payload.indexProps as number, 1); // remove the image from list which we have deleted
                    ProductModule.SET_GALLERY_LIST(this.fileArr);
                }

                SnackbarModule.setMsg(response.data.message as string);
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    uploadProdGallery(isFeatureImg: boolean): void {
        const formData = new FormData();
        let apiName = "";
        if (isFeatureImg) {
            this.isProdFISubmitting = true;
            apiName = "updateFeatureImg";
            formData.append("featured_image", this.featured_image);
        } else {
            this.isProdGalSubmitting = true;
            apiName = "updateProductGallery";
            // Multiple Product Gallery array
            if (this.product_galleries && this.product_galleries.length > 0) {
                Array.from(this.product_galleries).forEach(
                    (product_galleries) => {
                        formData.append(
                            "product_galleries[]",
                            <Blob>product_galleries
                        );
                    }
                );
            }
        }

        ProductModule[apiName]({
            images: formData,
            editId: this.model.id,
        }).then(
            (response: AxiosResponse<ResponseResult<IProductFullResponse>>) => {
                if (response.data.data) {
                    if (isFeatureImg) {
                        this.isProdFISubmitting = false;
                        this.featured_image = "";
                        // TODO: need to update the existing image
                        ProductModule.SET_VIEW_MODEL(
                            response.data.data as IProductFullResponse
                        );
                    } else {
                        this.product_galleries = [];
                        this.fileArr = response.data.data.product_galleries;
                        ProductModule.SET_GALLERY_LIST(this.fileArr);
                        this.isProdGalSubmitting = false;
                    }
                }

                // Success message
                SnackbarModule.setMsg(response.data.message as string);
            },
            (error) => {
                this.isProdGalSubmitting = false;
                this.errorMessage = this.getAPIErrorMessage(error.response);
            }
        );
    }

    checkIfImage(fileUrl: string): boolean {
        if (typeof fileUrl !== "string") return false;
        return !!fileUrl.match(/\w+\.(jpg|jpeg|gif|png|tiff|bmp)$/gi);
    }
}

export default MultiFileModal;
</script>
