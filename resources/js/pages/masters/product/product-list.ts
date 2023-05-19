/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "../../../components/DeleteModal.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import ColumnVisibilityBtn from "../../../components/ColumnVisibilityBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import MultiFileModal from "@/components/MultiFileModal.vue";
import Import from "../../../components/Import.vue";
import CommonApis from "../../../mixins/common-apis";
import ProductViewModal from "./ProductViewModal.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import ImageViewer from "@/components/ImageViewer.vue";
import Component, { mixins } from "vue-class-component";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";

import { CategoryModule } from "@/store/category";
import { ICategoryLightResponse } from "../../../../assets/types/category";
import { ProductModule } from "../../../store/product";
import { CommonModule } from "@/store/common";
import { ITableHeaders, IFilterModel } from "../../../../assets/types/table";

import {
    IProductGalleries,
    IProductFullResponse,
    IProductModel,
} from "../../../../assets/types/product";

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
import { SnackbarModule } from "@/store/snackbar";

@Component({
    components: {
        ErrorBlockServer,
        DeleteModal,
        ColumnVisibilityBtn,
        ExportBtn,
        MultiDelete,
        ProductViewModal,
        Import,
        MultiFileModal,
        ImageViewer,
    },
})
class Product extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "products";

    headers: ITableHeaders[] = [
        { text: "Name", value: "name" },
        { text: "Price", value: "price" },
        // { text: "Description", value: "description", sortable: false },
        { text: "Item Code", value: "item_code" },

        { text: "Category", value: "category.name" },
      //  { text: "Available Status", value: "available_status_text" },
        { text: "Stock", value: "stock" },
        { text: "Feature Image", value: "featured_image", sortable: false },
        { text: "Status", value: "status_text" },
        { text: "Actions", value: "actions", sortable: false },
    ];

    customSortableKeys = { available_status_text: "available_status" };
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
        store: "product", //as per Module Name
        modelName: "product", //as per Module Name
        multiple: false,
    };

    importZipProps: IImportProps = {
        store: "product",
        modelName: "product",
        zipName: "zipUpload",
        multiple: false,
    };
    exportProps: IExportProps = {
        ids: "",
        fileName: "Product",
        apiName: "products-export",
    };
    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    category_id = [];
    filterMenu = false;
    filterModel: IFilterModel = {};

    images: { thumbnail: string; source: string }[] = [];
    productViewModal = false;

    get categoryList(): ICategoryLightResponse[] {
        return CategoryModule.categoryList;
    }

    get galleryArr(): IProductGalleries[] {
        return ProductModule.galleryList;
    }

    /**
     * Redirect to add product page
     * */
    addProduct(): void {
        this["$router"].push("/masters/product/add");
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
     * Delete Product
     * @param id
     */

   /* deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "product";
        this.confirmation.title = this["$getConst"]("DELETE_TITLE");
        this.confirmation.description = this["$getConst"]("WARNING");
        this.modalOpen = true;
    }*/

    /**
     * Multiple Delete
     */
    multipleDelete(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element) => {
            rowIds.push(element.id);
        });

        this.deleteProps.ids = rowIds;
        this.deleteProps.store = "product";
        (<any>this.$refs.multipleDeleteBtn).deleteMulti();
    }

    onEdit(id: string | number): void {
        ProductModule.SET_EDIT_ID(id);
        this["$router"].push("/masters/product/edit/" + id);
    }
    /**
     * View Product
     * @param id
     */
    onView(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        ProductModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IProductFullResponse>>) => {
                ProductModule.SET_VIEW_MODEL(
                    response.data.data as IProductFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                this.productViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    /**
     * Change Filter
     */
    changeFilter() {
        const filter: IFilterModel = {};

        if (this.category_id.length > 0) {
            filter.pf = {
                categories: this.category_id,
            } as any;
        }

        this.filterModel = filter;
        this.refresh();
        this.filterMenu = false;
    }

    /**
     * Reset Filter
     */
    resetFilter(): void {
        this.category_id = [];
        this.changeFilter();
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

    /*
     * Open Gallery
     * @param row
     * */
    openGallery(row: IProductFullResponse): void {
        this.images = [];
        const index = (this.tableData as IProductFullResponse[]).findIndex(
            (item) => item.id == row.id
        );
        if (index != undefined && index >= 0) {
            for (let i = 0; i < row.product_galleries.length; i++) {
                const obj = {
                    thumbnail: row.product_galleries[i].gallery_thumbnail,
                    source: row.product_galleries[i].gallery,
                };
                this.images.push(obj);
                this.$forceUpdate();
            }
            const self = this;
            setTimeout(() => {
                (<any>this.$refs.imgViewer).show(self.images, 0);
            }, 500);
        }
    }

    /**
     * //Manage Gallery
     * Open Modal box for file management
     */
    openGalleryModal(id: string): void {
        HTMLClassModule.addBodyClassName("page-loading");
        ProductModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IProductFullResponse>>) => {
                ProductModule.SET_VIEW_MODEL(
                    response.data.data as IProductFullResponse
                );
                HTMLClassModule.removeBodyClassName("page-loading");
                (<any>this.$refs.managegallerymodal).manageFileModal();
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    onStatusChange(id: string, status: string): void {
        HTMLClassModule.addBodyClassName("page-loading");

        if (status == "1") {
            status = "0"; // request deactivation when user status is active
        } else if (status == "0") {
            status = "1"; // request activation when user status is inactive
        }

        ProductModule.setProductStatus({
            editId: id,
            status: status,
        }).then(
            (response: AxiosResponse<ResponseResult<IProductModel>>) => {
                this.refresh();
                SnackbarModule.setMsg(response.data.message as string);

                HTMLClassModule.removeBodyClassName("page-loading");
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    created(): void {
        HTMLClassModule.addBodyClassName("page-loading");

        /*Single Master : Start */
        CommonModule.getAll({
            apiName: "categories",
            pagination: { isLight: true },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                CategoryModule.SET_CATEGORY_LIST(
                    <ICategoryLightResponse[]>response.data.data
                );
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

        /*Single Master : End */
    }
}
export default Product;
