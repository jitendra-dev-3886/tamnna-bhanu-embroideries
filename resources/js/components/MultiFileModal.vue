<template>
    <v-dialog
        :value="fileModal"
        @click:outside="onCancel()"
        @keydown.esc="onCancel"
        content-class="modal-dialog"
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
                    <v-flex xs12>
                        <table class="table">
                            <thead>
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
                                                    height="70px"
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
                                                                index
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
import { IObject, ResponseResult, IParamProps } from "../../assets/types/common";
import { AxiosResponse } from "axios";

@Component({
    components: {
        ErrorBlockServer,
        DeleteConfirm,
    },
})
class MultiFileModal extends Mixins(CommonServices) {
    @Prop({ default: () => [] }) fileArr!: IObject[];
    @Prop({ default: "" }) storeName!: string;
    @Prop({ default: "" }) variableMutation!: string;
    @Prop({ default: "" }) filePath!: string;
    @Prop({ default: false }) value!: boolean;

    email = "";
    deleteConfirm = false;
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    fileModal = false;
    deleting = false;

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
    confirmDelete(id: string | number, index: number): void {
        this.paramProps.idProps = id;
        this.paramProps.indexProps = index;
        this.deleteConfirm = true;
    }

    /* Delete Image */
    deleteGallery(payload: IParamProps): void {
        this.deleteConfirm = false;
        this.deleting = true;
        this.$store
            .dispatch(
                `${this.storeName}/delete${this.variableMutation}`,
                payload.idProps
            )
            .then(
                (response: AxiosResponse<ResponseResult<boolean>>) => {
                    this.deleting = false;
                    this.fileArr.splice(payload.indexProps as number, 1); // remove the image that we want to delete
                    this.$store.commit(
                        `${
                            this.storeName
                        }/SET_${this.variableMutation.toLocaleUpperCase()}_LIST`,
                        this.fileArr
                    ); // set file list
                    SnackbarModule.setMsg(response.data.message);
                    this.$parent["getData"]();
                    this.$emit("delete-success");
                },
                (error) => {
                    this.showDialog(this.getAPIErrorMessage(error.response));
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
