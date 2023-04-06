import _debounce from "lodash.debounce";
import CommonServices from "../../mixins/common";
import { HTMLClassModule } from "../../store/htmlclass";
import Component from "vue-class-component";
import { IObject, ResponseResult } from "../../../assets/types/common";
import {
    IFilterModel,
    ITableHeaders,
    ITableOptions,
} from "../../../assets/types/table";

@Component
class CustomTable extends CommonServices {
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
    footerProps: IObject = {
        "items-per-page-options": [10, 20, 30, 50, 100],
        showFirstLastPage: true,
    };

    /**
     * return current statename/storename
     * need to be pass from js
     * @returns {*}
     */
    get state(): any {
        const urlArray = this.urlApi != "" ? this.urlApi.split("/") : "";
        // eslint-disable-next-line prefer-destructuring,vue/no-side-effects-in-computed-properties
        this.stateName = urlArray[0];
        return this.$store.state[this.stateName];
    }

    /**
     * return array of objects for table data
     * @returns {*}
     */
    get tableData(): IObject[] {
        return this.state.tableData.data;
    }

    /**
     * return number of current page
     * @returns {*}
     */
    get currentPage(): string | number {
        return this.state.tableData.current_page;
    }

    /**
     * return limit/per page value for data table
     * @returns {*}
     */
    get limit(): string | number {
        return this.state.pagination.limit;
    }

    /**
     * return total number of items on server for table pagination
     * @returns {number}
     */
    get pageCount(): string | number {
        return this.state.tableData.total ? this.state.tableData.total : 0;
    }

    get onUpdateOptions(): typeof _debounce {
        return _debounce(this.updateTable, 100);
    }

    get onSearch(): typeof _debounce {
        return _debounce(this.updateTable, 500);
    }

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
    /**
     * set table's current options to store and call api to get data
     */
    updateTable(): void {
        // const tableOptions = this.$refs.table.options; Not working in TS
        //Need review again to get rid indexer access
        const tableOptions = this.options;
        this.$store.commit(`${this.stateName}/SET_PAGINATION`, {
            query: this.searchModel,
            page: tableOptions.page,
            limit: tableOptions.itemsPerPage,
            orderBy:
                tableOptions.sortBy && tableOptions.sortBy.length > 0
                    ? tableOptions.sortBy[0]
                    : "",
            descending:
                tableOptions.sortDesc && tableOptions.sortDesc.length > 0
                    ? tableOptions.sortDesc[0]
                    : "default",
            filter: this.filterModel
                ? this.convetFiltertoBase64(this.filterModel)
                : "",
        });
        this.getData();
    }
    /**
     * reset pagination data but except filter
     */
    resetPagination(): void {
        this.$store.commit(`${this.stateName}/SET_PAGINATION`, {
            query: this.searchModel,
            page: 1,
            limit: this.state.pagination.limit,
            orderBy: this.state.pagination.orderBy,
            descending: this.state.pagination.descending,
            filter: this.filterModel
                ? this.convetFiltertoBase64(this.filterModel)
                : "",
        });
    }
    setData(response: ResponseResult<IObject[]>): void {
        this.$store.commit(`${this.stateName}/SET_TABLE_DATA`, response.data);
    }
    /**
     * call api to get data
     */
    // eslint-disable-next-line consistent-return
    getData(promiseOnly?: boolean): void | Promise<IObject[]> {
        if (this.$refs.table && this.urlApi && this.urlApi != "") {
            return new Promise((resolve, reject) => {
                this.loading = true;
                this.$store.dispatch(this.urlApi, this.state.pagination).then(
                    (response) => {
                        this.loading = false;
                        HTMLClassModule.removeBodyClassName("page-loading");
                        if (promiseOnly) {
                            // if parse promiseOnly then resolve response data else set data directly in table
                            resolve(response);
                        } else {
                            this.setData(response);
                        }
                    },
                    (error) => {
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
        newHeaders.sort(function (a, b) {
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

export default CustomTable;
