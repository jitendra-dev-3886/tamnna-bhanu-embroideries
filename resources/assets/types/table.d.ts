type ArrayOfObject = { [index: number]: string }[];
export interface ITableOptions {
    groupBy?: ArrayOfObject;
    groupDesc?: ArrayOfObject;
    itemsPerPage?: number;
    multiSort?: number;
    mustSort?: boolean;
    page?: number;
    sortBy?: ArrayOfObject;
    sortDesc?: ArrayOfObject;
}
export type IFilterModel = {
    [key: string]: [string | undefined] | string | string[];
};

export interface ITableHeaders {
    text: string;
    value: string;
    align?: "start" | "center" | "end";
    sortable?: boolean;
    filterable?: boolean;
    groupable?: boolean;
    divider?: boolean;
    class?: string | string[];
    cellClass?: string | string[];
    width?: string | number;
    filter?: (value: any, search: string, item: any) => boolean;
    sort?: (a: any, b: any) => number;
}
export interface ITableFooters {
    "disable-items-per-page"?: boolean;
    "disable-pagination"?: boolean;
    "first-icon"?: string;
    "items-per-page-all-text"?: string;
    "items-per-page-options"?: number[];
    "items-per-page-text"?: string;
    "last-icon"?: string;
    "next-icon"?: string;
    options?: {
        page: number;
        itemsPerPage: number;
        sortBy: string[];
        sortDesc: boolean[];
        groupBy: string[];
        groupDesc: boolean[];
        multiSort: boolean;
        mustSort: boolean;
    };
    "page-text"?: string;
    pagination?: {
        page: number;
        itemsPerPage: number;
        pageStart: number;
        pageStop: number;
        pageCount: number;
        itemsLength: number;
    };
    "prev-icon"?: string;
    "show-current-page"?: boolean;
    "show-first-last-page"?: boolean;
}
