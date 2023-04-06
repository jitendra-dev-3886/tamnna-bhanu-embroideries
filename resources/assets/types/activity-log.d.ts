import { IObject } from "./common";

export interface IModuleNames {
    id: string;
    name: string;
}
export interface ITableModalData {
    tableData: IObject[];
    col: string;
}
export interface IObjectModalData {
    modelData: IObject;
    col: string;
}
export interface IModel {
    created_at: string | Date;
    log_name: string;
    description: string;
    is_address: string;
    causer_id: string;
    user_id: [...string[]];
    module: [...string[]];
    period_type: string;
    start_date: string | Date;
    end_date: string | Date;
    is_all_date: string;
    is_all_module: string;
    is_all_user: string;
}

export interface IPeriodTypeItems {
    enum: string;
    name: string;
}
