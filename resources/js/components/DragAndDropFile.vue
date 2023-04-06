<template>
    <form
        method="POST"
        name="import-csv-form"
        class="import-csv-form"
        role="form"
        novalidate
        autocomplete="off"
    >
        <v-layout row wrap class="flex-center mb-6">
            <div class="example-drag">
                <div class="upload">
                    <div v-if="files.length" class="mb-5 text-center">
                        <div v-for="(file, index) in files" :key="file.id">
                            <div class="mb-1">
                                <b>File Name:</b> {{ file.name }}
                            </div>
                            <div class="mb-1">
                                <b>File Size:</b> {{ getFileSize(file.size) }}
                            </div>
                            <div v-if="file.error" class="mb-1">
                                <b>Error:</b> {{ file.error }}
                            </div>
                            <div v-else-if="file.success" class="mb-1">
                                success
                            </div>
                            <div v-else-if="file.active" class="mb-1">
                                active
                            </div>
                            <div v-else />
                        </div>
                    </div>
                    <div v-else>
                        <div>
                            <div class="text-center p-5">
                                <img
                                    src="/images/drag-and-drop-icon.png"
                                    class="
                                        img-responsive
                                        drag-drop-icon
                                        mb-4
                                        mt-4
                                    "
                                    alt="Drag and drop icon"
                                    aria-label="Drag and drop icon"
                                    title="Drag and drop icon"
                                />
                                <h4 class="mb-6">
                                    <template
                                        v-if="
                                            importProps.zipName == 'zipUpload'
                                        "
                                    >
                                        {{ $getConst("ZIP_FILE_UPLOAD_MSG") }}
                                    </template>
                                    <template v-else>
                                        {{ $getConst("FILE_UPLOAD_MSG") }}
                                    </template>
                                </h4>
                                <div class="position-relative">
                                    <span class="position-absolute or-txt"
                                        >or</span
                                    >
                                    <v-divider></v-divider>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-show="$refs.upload && $refs.upload.dropActive"
                        class="drop-active"
                    >
                        <h3>{{ $getConst("DRAG_AND_DROP_MSG") }}</h3>
                    </div>

                    <div class="example-btn">
                        <file-upload
                            ref="upload"
                            v-model="files"
                            name="file_upload"
                            v-validate="getFileUploadValidation"
                            class="
                                btn btn-grey
                                font-weight-bold font-size-3
                                w100
                            "
                            :multiple="importProps.multiple"
                            :drop="true"
                            :drop-directory="true"
                            aria-label="File upload"
                        >
                            <i class="fa fa-plus"></i>
                            <template v-if="importProps.zipName == 'zipUpload'">
                                {{ $getConst("SELECT_ZIP_LABEL") }}
                            </template>
                            <template v-else>
                                {{ $getConst("SELECT_FILE_LABEL") }}
                            </template>
                        </file-upload>
                    </div>
                    <div class="flex-center">
                        <button
                            type="button"
                            class="
                                btn btn-primary
                                font-weight-bold font-size-3
                                w100
                                mt-4
                            "
                            :disabled="files.length == 0"
                            @click.prevent="uploadCsv"
                            v-if="!files.length > 0"
                            aria-label="Submit"
                        >
                            <v-icon class="fa fa-upload" />
                            {{ $getConst("BTN_SUBMIT") }}
                        </button>
                        <v-btn
                            :loading="isLoading"
                            color="primary"
                            class="btn font-weight-bold font-size-3 w100 mt-4"
                            :disabled="files.length == 0"
                            @click.prevent="uploadCsv"
                            v-if="files.length > 0"
                            aria-label="Submit button with loader"
                        >
                            {{ $getConst("BTN_SUBMIT") }}
                        </v-btn>
                    </div>
                    <div class="d-block mt-3" v-if="!this.importProps.zipName">
                        <span class="float-left mb-5">
                            <a class="font-size-5" @click="downloadSampleFile()"
                                >Download sample</a
                            >
                        </span>
                        <span class="float-right"> Max size: 5MB</span>
                    </div>
                    <div class="d-block mt-3" v-if="this.importProps.zipName">
                        <span class="text-center d-block"> Max size: 40MB</span>
                    </div>
                </div>
            </div>
        </v-layout>
        <import-error-modal
            v-model="importErrorDialog"
            :import-error-arr="importErrorArr"
        />
    </form>
</template>
<script lang="ts">
/**
 * sample csv import file
 */
import FileUpload from "vue-upload-component";

/**
 * end of sample csv import file
 */
import CommonServices from "../mixins/common";
import ImportErrorModal from "./ImportErrorModal.vue";
import { Component, Mixins, Prop } from "vue-property-decorator";
import { ICurrentUserData } from "../../assets/types/user";
import { IImportProps } from "../../assets/types/common";
import { UserModule } from "../store/user";
import { SnackbarModule } from "../store/snackbar";
import { IObject } from "../../assets/types/common";
import { IDragAndDropValidations } from "../../assets/types/partials";

@Component({
    components: {
        FileUpload,
        ImportErrorModal,
    },
})
class DragAndDropFile extends Mixins(CommonServices) {
    @Prop({ default: {} }) importProps!: IImportProps;
    files: IObject[] = [];
    importErrorArr: IObject[] = [];
    importErrorDialog = false;
    validationMessages: IDragAndDropValidations = {
        file_upload: [
            { key: "size", value: "File size should be less than 5 MB" },
        ],
    };
    isLoading = false;

    get currentUser(): ICurrentUserData {
        return UserModule.currentUserData;
    }

    get getFileUploadValidation(): string {
        let valdationrule = "size:5000|ext:csv";
        if (this.importProps.zipName == "zipUpload") {
            valdationrule = `size:40000|ext:zip`;
        }
        return valdationrule;
    }

    /**
     * Download csv
     */
    downloadSampleFile(): void {
        if (
            this.currentUser.sample_excels[0][
                "sample_" + this.importProps.modelName
            ]
        ) {
            this.downloadFile(
                this.currentUser.sample_excels[0][
                    "sample_" + this.importProps.modelName
                ],
                "DOWNLOAD_SAMPLE_CSV"
            );
        }
    }

    /**
     * upload csv
     */
    uploadCsv(): void {
        this.$validator.validate().then((valid) => {
            if (valid) {
                var apiName = "import";
                var formData = new FormData();
                if (this.importProps.zipName == "zipUpload") {
                    apiName = "importZip";
                }
                if (this.importProps.multiple == false) {
                    if (this.files[0].file instanceof File) {
                        formData.append("file", this.files[0].file as Blob);
                    }
                } else {
                    for (var index in this.files) {
                        if (this.files[index].file instanceof File) {
                            formData.append(
                                `file[${parseInt(index, 10)}]`,
                                this.files[index].file as Blob
                            );
                        }
                    }
                }
                this.isLoading = true;
                this.$store
                    .dispatch(`${this.importProps.store}/${apiName}`, formData)
                    .then(
                        () => {
                            this.isLoading = false;
                            SnackbarModule.setMsg(
                                this.$getConst("FILE_UPLOADED")
                            );
                            this.files = [];
                            this.$parent.$parent["getData"]();
                        },
                        (error) => {
                            this.isLoading = false;
                            if (
                                error.response.data.errors &&
                                error.response.data.errors.length > 0
                            ) {
                                this.importErrorArr =
                                    error.response.data.errors;
                                this.importErrorDialog = true;
                            } else {
                                this.showDialog(
                                    this.getAPIErrorMessage(error.response)
                                );
                            }
                            this.files = [];
                            this.$emit("refresh-table");
                        }
                    );
            }
        });
    }
}

export default DragAndDropFile;
</script>
