export interface ICustomerModel {
    name: string;
    company_name:string;
    city: string;
    mobileno: string;
    status:string|null;
}

export interface ICustomerParams {
    model: ICustomerModel;
    status?: string | number
}

export type ValidationObj = { key: string, value: string }[];

export interface IUserValidations {
    name: ValidationObj;
    company_name:ValidationObj;
    city: ValidationObj;
    mobileno: ValidationObj;
    status:ValidationObj
}

