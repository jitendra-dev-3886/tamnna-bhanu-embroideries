import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";
import CategoryViewModal from "./CategoryViewModal.vue";
import CategoryEditImages from "./CategoryEditImages.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { CategoryModule } from "../../../store/category";

import {
    ITableHeaders,
} from "../../../../assets/types/table";

import {
    ICategoryFullResponse,
} from "../../../../assets/types/category";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";

import {
    IConfirmationProps,
    IDeleteProps,
    IExportProps,
    IImportProps,
    IParamProps,
    ResponseResult,
} from "../../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        CategoryViewModal,
        CategoryEditImages,
        Import,
    },
})
class Category extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "categories";

    headers: ITableHeaders[] = [
        { text: 'Name', value: 'name' },
        //TODO: set value as per backend response 
        //{ text: 'Parent Categort', value: '' },
       // { text: 'Description', value: 'description' },
        { text: 'Feature Image', value: 'featured_image' ,sortable: false},
        { text: 'Actions', value: 'actions', sortable: false },
            ];

    customSortableKeys = {
            };
    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this["$getConst"]("BTN_CANCEL"),
        btnConfirmationText: this["$getConst"]("BTN_OK"),
    };
    deleteProps: IDeleteProps = {
        ids: "",
        store: "",
    };
    importProps: IImportProps = {
        store: "category", //as per Module Name
        modelName: "category", //as per Module Name
        multiple: false,
    };

    exportProps: IExportProps = {
        ids: "",
        fileName: "Category",
        apiName: "categories-export",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };


    categoryViewModal = false;
    categoryEditImages=false;



    /**
     * Redirect to add category page
     * */
    addCategory(): void {
        this["$router"].push("/masters/category/add");
    }

    /**
     * Export data in CSV file
     */
    setExport(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element, index) => {
            rowIds[index] = element.id;
        });

        this.exportProps.ids = rowIds;
        (<any>this.$refs.exportbtn).exportToCSV();
    }

    /**
     * Delete Category
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "category";
        this.confirmation.title = this["$getConst"]("DELETE_TITLE");
        this.confirmation.description = this["$getConst"]("WARNING");
        this.modalOpen = true;
    }

    /**
     * Multiple Delete
     */
    multipleDelete(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element) => {
            rowIds.push(element.id);
        });

        this.deleteProps.ids = rowIds;
        this.deleteProps.store = "category";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }

    onEdit(id: string | number): void {
        CategoryModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/category/edit/" + id);
    }

    onEditImage(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CategoryModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<ICategoryFullResponse>>) => {
                CategoryModule.SET_VIEW_MODEL(
                    response.data.data as ICategoryFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.categoryEditImages = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    /**
     * View Category
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CategoryModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<ICategoryFullResponse>>) => {
                CategoryModule.SET_VIEW_MODEL(
                    response.data.data as ICategoryFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.categoryViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
    refreshData(): void {
        const self = this;
        setTimeout(function () {
            if (self.tab == "tab1") {
                self.refresh();
            } else if (self.tab == "tab2" && self.$refs.importdata) {
                if (self.isImportLoaded) {
                     (self.$refs.importdata as any).refreshImport();
                }
                self.isImportLoaded = true;
            }
        }, 100);
    }
    created(): void {

    }
}
export default Category;
