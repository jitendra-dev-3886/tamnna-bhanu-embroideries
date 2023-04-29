export interface ICustomerModel {
    id:string;
    name: string;
    company_name:string;
    city: string;
    mobileno: string;
    user_status?: string;

}
export interface ICustomerParams {
    user_status: string|number;
    editId?: string | number;
    remember_me?: string | number;
}

export type ValidationObj = { key: string, value: string }[];

export interface ICustomerValidations {
    name: ValidationObj;
    company_name:ValidationObj;
    city: ValidationObj;
    mobileno: ValidationObj;
    user_status:ValidationObj
}

