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
                <v-tabs v-model="modelTab">
                    <v-tab ripple href="#tab1"> Featured Image </v-tab>
                    <v-tab ripple href="#tab2"> Product Gallery Images </v-tab>
                </v-tabs>
                <v-tabs-items class="mt-2" v-model="modelTab">
                    <v-tab-item value="tab1">
                        <v-card>
                            <v-card-text>
                                <div>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm10 lg12 class="mb-2">
                                            <a
                                                :href="model.featured_image"
                                                target="_blank"
                                                ><img
                                                    :src="model.featured_image"
                                                    width="100%"
                                                    height="250px"
                                                    class="mb-3"
                                                />
                                            </a>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm10 lg10>
                                            <v-file-input
                                                id="featured_image"
                                                ref="featured_image"
                                                v-model="featured_image"
                                                v-validate="
                                                    'ext:jpeg,png,jpg|size:1024'
                                                "
                                                attach
                                                name="featured_image"
                                                label="Feature Image*"
                                                :error-messages="
                                                    getErrorValue(
                                                        'featured_image',
                                                        errors,
                                                        validationMessages
                                                    )
                                                "
                                                accept="image/jpg, image/jpeg, image/png"
                                                :persistent-hint="true"
                                                hint="Extension: jpg, jpeg, png | Size: Maximum 1024KB"
                                                counter="1"
                                                @click:clear="
                                                    featured_image = ''
                                                "
                                                aria-label="Featured_image"
                                            />
                                        </v-flex>
                                        <v-flex xs12 sm2 lg2 class="pl-2 pt-2">
                                            <v-btn
                                                color="primary"
                                                type="submit"
                                                :loading="isProdFISubmitting"
                                                :disabled="
                                                    featured_image == '' ||
                                                    getErrorCount(
                                                        'featured_image',
                                                        errors
                                                    ) == true
                                                "
                                                @click="uploadProdGallery(true)"
                                                class="btn btn-theme float-xs-none"
                                                >Update
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="tab2">
                        <v-card>
                            <v-card-text>
                                <div>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm10 lg10>
                                            <v-file-input
                                                v-model="product_galleries"
                                                v-validate.continues="
                                                    `ext:jpeg,png,jpg|size:${maxSizeLeft}|valid_file_length:${maxFileCountLeft}`
                                                "
                                                multiple
                                                name="product_galleries"
                                                accept="image/jpg, image/jpeg, image/png"
                                                :persistent-hint="true"
                                                :disabled="fileArr.length == 5"
                                                :hint="`Extension: jpg, jpeg, png | Size: Maximum ${maxSizeLeft}KB`"
                                                :counter="maxFileCountLeft"
                                                label=" Product Galleries"
                                                @click:clear="
                                                    product_galleries = []
                                                "
                                                aria-label="Product Galleries"
                                                :error-messages="
                                                    getErrorValue(
                                                        'product_galleries',
                                                        errors,
                                                        validationMessages
                                                    )
                                                "
                                            />
                                        </v-flex>
                                        <v-flex xs12 sm2 lg2 class="pl-2 pt-2">
                                            <v-btn
                                                color="primary"
                                                type="submit"
                                                :loading="isProdGalSubmitting"
                                                :disabled="
                                                    product_galleries.length ==
                                                        0 ||
                                                    getErrorCount(
                                                        'product_galleries',
                                                        errors
                                                    ) == true
                                                "
                                                @click="
                                                    uploadProdGallery(false)
                                                "
                                                class="btn btn-theme float-xs-none"
                                                >Upload
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm10 lg12 class="mt-5">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Existing Gallery
                                                            Images
                                                        </th>
                                                    </tr>

                                                    <tr class="no-border">
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="fileArr">
                                                    <template
                                                        v-if="
                                                            fileArr.length != 0
                                                        "
                                                    >
                                                        <tr
                                                            :key="singleFile.id"
                                                            v-for="(
                                                                singleFile,
                                                                index
                                                            ) in fileArr"
                                                        >
                                                            <td>
                                                                <a
                                                                    aria-label="File"
                                                                    :href="
                                                                        singleFile[
                                                                            filePath
                                                                        ]
                                                                    "
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
                                                                                singleFile[
                                                                                    filePath
                                                                                ]
                                                                            )
                                                                        "
                                                                        alt="View image in new tab"
                                                                        aria-label="View image in new tab"
                                                                        title="Thumbnail"
                                                                    />
                                                                    <p
                                                                        :class="{
                                                                            'mt-2': checkIfImage(
                                                                                singleFile[
                                                                                    filePath
                                                                                ]
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
                                                            <td
                                                                style="
                                                                    width: 25%;
                                                                "
                                                            >
                                                                <v-tooltip
                                                                    bottom
                                                                >
                                                                    <template
                                                                        v-slot:activator="{
                                                                            on,
                                                                        }"
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
                                                                            v-on="
                                                                                on
                                                                            "
                                                                            aria-label="Delete image"
                                                                        >
                                                                            {{
                                                                                icons.mdiDelete
                                                                            }}
                                                                        </v-icon>
                                                                    </template>
                                                                    <span>{{
                                                                        $getConst(
                                                                            "DELETE_TOOLTIP"
                                                                        )
                                                                    }}</span>
                                                                </v-tooltip>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <tr v-else>
                                                        <td
                                                            colspan="2"
                                                            style="
                                                                text-align: center;
                                                            "
                                                        >
                                                            No Data available
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </v-flex>
                                    </v-layout>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs-items>
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

    modelTab = "tab1";

    get model(): IProductFullResponse {
        return ProductModule.viewModel;
    }

    validationMessages: any = {
        featured_image: [
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "size",
                value: `Maximum size allowed is 1024 KB`,
            },
        ],
        product_galleries: [
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "valid_file_length",
                value: `Maximum file uploads limit crossed`,
            },
            {
                key: "size",
                value: `Maximum size allowed is ${this.maxSizeLeft} KB`,
            },
        ],
    };

    get maxFileCountLeft(): number {
        let m: number = 5 - this.fileArr.length;
        return m;
    }

    get maxSizeLeft(): number {
        let ms: number = 1024 * this.maxFileCountLeft;
        return ms;
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
                    this.$validator.reset();
                    this.fileArr.splice(payload.indexProps as number, 1); // remove the image from list which we have deleted
                    ProductModule.SET_GALLERY_LIST(this.fileArr);
                }
                (this.$parent as any)["getData"]();

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

                (this.$parent as any)["getData"]();

                // Success message
                SnackbarModule.setMsg(response.data.message as string);
            },
            (error) => {
                this.isProdFISubmitting = false;
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
