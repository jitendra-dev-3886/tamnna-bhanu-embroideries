import _debounce from "lodash.debounce";
import CommonServices from "../../mixins/common";
import { HTMLClassModule } from "../../store/htmlclass";
import Component from "vue-class-component";
import { ResponseResult } from "../../../assets/types/common";
import {
    IFilterModel,
    ITableFooters,
    ITableHeaders,
    ITableOptions
} from "../../../assets/types/table";
import { CommonModule } from "../../store/common";

@Component
class ServerTable extends CommonServices {
    loading = false;
    singleSelect = false;
    selected: { [index: string]: string }[] = [];
    stateName = "";
    urlApi = "";
    searchModel = "";
    filterModel: IFilterModel = {};
    toggleSelect = false;
    headers: ITableHeaders[] = [];
    selectedHeaders: ITableHeaders[] = this.headers;
    options: ITableOptions = { mustSort: true };
    footerProps: ITableFooters = {
        "items-per-page-options": [10, 20, 30, 50, 100],
        "show-first-last-page": true
    };
    customSortableKeys = {};

    /**
     * return array of objects for table data
     * @returns {*}
     */
    get tableData(): unknown {
        return CommonModule.tableData.data;
    }

    /**
     * return number of current page
     * @returns {*}
     */
    get currentPage(): number {
        return CommonModule.tableData.current_page as number;
    }

    /**
     * return limit/per page value for data table
     * @returns {*}
     */
    get limit(): number {
        return CommonModule.pagination.limit as number;
    }

    /**
     * return total number of items on server for table pagination
     * @returns {number}
     */
    get pageCount(): string | number {
        return CommonModule.tableData.total ? CommonModule.tableData.total : 0;
    }

    // get onUpdateOptions(): typeof _debounce {
    //     return _debounce(this.updateTable, 100);
    // }

    // get onSearch(): typeof _debounce {
    //     return _debounce(this.updateTable, 500);
    // }

    public onUpdateOptions = _debounce(this.updateTable, 100);

    public onSearch = _debounce(this.updateTable, 500);

    onSelectColumnAll(checked: boolean): void {
        const list = (<any>this.$refs.tableData).data;
        const rowIds: { [index: number]: string }[] = [];
        if (checked) {
            list.forEach((element, index) => {
                rowIds[index] = element.id;
            });
            this.selected = rowIds;
        } else {
            this.selected = [];
        }
    }

    resetMarkedRows(): void {
        this.selected = [];
    }

    getCustomSortKey(defaultSortKey: string): string {
        if (
            Object.prototype.hasOwnProperty.call(
                this.customSortableKeys,
                defaultSortKey
            )
        ) {
            return this.customSortableKeys[defaultSortKey];
        }
        return defaultSortKey;
    }
    /**
     * set table's current options to store and call api to get data
     */
    updateTable(): void {
        const tableOptions = this.options;

        CommonModule.SET_PAGINATION({
            query: this.searchModel,
            page: tableOptions.page,
            limit: tableOptions.itemsPerPage,
            orderBy:
                tableOptions.sortBy && tableOptions.sortBy.length > 0
                    ? this.getCustomSortKey(tableOptions.sortBy[0] as string)
                    : "",
            descending:
                tableOptions.sortDesc && tableOptions.sortDesc.length > 0
                    ? (tableOptions.sortDesc[0] as string)
                    : "default",
            filter: this.filterModel
                ? this.convetFiltertoBase64(this.filterModel)
                : ""
        });
        this.getData();
    }
    /**
     * reset pagination data but except filter
     */
    resetPagination(): void {
        CommonModule.SET_PAGINATION({
            query: this.searchModel,
            page: 1,
            limit: CommonModule.pagination.limit,
            orderBy: CommonModule.pagination.orderBy,
            descending: CommonModule.pagination.descending,
            filter: this.filterModel
                ? this.convetFiltertoBase64(this.filterModel)
                : ""
        });
    }
    setData(response: ResponseResult<unknown>): void {
        CommonModule.SET_TABLE_DATA(response);
    }
    /**
     * call api to get data
     */
    // eslint-disable-next-line consistent-return
    getData(promiseOnly?: boolean): void | Promise<unknown> {
        this.setData({ data: [] });
        if (this.$refs.table && this.urlApi && this.urlApi != "") {
            return new Promise((resolve, reject) => {
                this.loading = true;
                CommonModule.getAll({
                    apiName: this.urlApi,
                    pagination: CommonModule.pagination
                }).then(
                    response => {
                        this.loading = false;
                        HTMLClassModule.removeBodyClassName("page-loading");
                        if (promiseOnly) {
                            resolve(response);
                        } else {
                            this.setData(response);
                        }
                    },
                    error => {
                        this.loading = false;
                        HTMLClassModule.removeBodyClassName("page-loading");
                        this.setData({ data: [] });
                        reject(error);
                    }
                );
            });
        }
    }
    /**
     * reset pagination data but except filter and get data
     */
    refresh(): void {
        if (this.$refs.table && this.urlApi && this.urlApi != "") {
            this.resetPagination();
            this.getData();
        }
    }
    setSelectedHeaders(newHeaders: ITableHeaders[]): void {
        const self = this;
        newHeaders.sort(function(a, b) {
            return self.headers.indexOf(a) - self.headers.indexOf(b);
        });
        this.selectedHeaders = newHeaders;
    }
    closeMenuColumn(): void {
        this.toggleSelect = false;
    }
    created(): void {
        this.selectedHeaders = this.headers;
    }
}

export default ServerTable;
