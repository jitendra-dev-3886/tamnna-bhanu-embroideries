<template>
    <span>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    color="info"
                    dark
                    class="mb-2 mr-2"
                    v-on="on"
                    aria-label="Export Button"
                >
                    <v-icon small>{{ icons.mdiDownload }}</v-icon>
                </v-btn>
            </template>
            <span>Export</span>
        </v-tooltip>
    </span>
</template>

<script lang="ts">
import CommonServices from "../mixins/common";
import { Component, Mixins, Prop } from "vue-property-decorator";
import { ResponseResult, IExportProps } from "../../assets/types/common";
import { CommonModule } from "../store/common";
import { AxiosResponse } from "axios";
import { IPagination } from "../../assets/types/common";

@Component
class ExportBtn extends Mixins(CommonServices) {
    @Prop({
        default: {
            ids: "",
            store: "",
            fileName: "",
            pagination: "",
            apiName: ""
        }
    })
    exportProps!: IExportProps;

    get exportPagination(): IPagination {
        return JSON.parse(JSON.stringify(CommonModule.pagination));
    }

    exportToCSV(): void {
        if (this.exportProps.ids.length > 0) {
            this.exportPagination.filter = this.convetFiltertoBase64({
                id: this.exportProps.ids
            });
        }
        CommonModule.export({
            apiName: this.exportProps.apiName as string,
            param: this.exportPagination
        }).then(
            (response: AxiosResponse<ResponseResult<string>>) => {
                this.convertToCSV(
                    this.exportProps.fileName,
                    response.data as string
                );
            },
            error => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
}
export default ExportBtn;
</script>
