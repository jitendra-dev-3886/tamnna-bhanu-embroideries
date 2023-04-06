import ImportErrorModal from "./ImportErrorModal.vue";
import DragAndDropFile from "./DragAndDropFile.vue";
import { Component, Prop } from "vue-property-decorator";
import {
    IImportProps,
    IImportCSVLogResponse,
    ResponseResult,
} from "../../assets/types/common";
import { IFilterModel, ITableHeaders } from "../../assets/types/table";
import { AxiosResponse } from "axios";
import { CommonModule } from "../store/common";
import ServerTable from "../mixins/customtable/server-table";

@Component({
    components: {
        ImportErrorModal,
        DragAndDropFile,
    },
})
class Import extends ServerTable {
    @Prop({ default: "" }) importProps!: IImportProps;
    file: File | undefined;
    urlApi = `import-csv-log`;
    filterModel: IFilterModel = {
        model_name: [this.importProps.modelName],
    };
    headers: ITableHeaders[] = [
        { text: "File", value: "filename" },
        { text: "Date", value: "created_at" },
        { text: "No.of Rows", value: "no_of_rows" },
        { text: "User", value: "user_id" },
        { text: "Status", value: "status" },
        { text: "Actions", value: "actions", sortable: false },
    ];

    customSortableKeys = {
        status_text: "status",
    };

    importErrorArr: IImportCSVLogResponse[] = [];
    importErrorDialog = false;

    /**
     * refresh Table Data
     */
    refreshImport(): void {
        this.refresh();
    }
    /**
     *View error detail
     * @param id
     */
    onView(id: string | number): void {
        CommonModule.getByImportId(id.toString()).then(
            (
                response: AxiosResponse<ResponseResult<IImportCSVLogResponse[]>>
            ) => {
                this.importErrorArr = response.data
                    .data as IImportCSVLogResponse[];
                this.importErrorDialog = true;
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
}

export default Import;