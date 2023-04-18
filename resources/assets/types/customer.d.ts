export interface ICustomerModel {
    name: string;
    company_name:string;
    city: string;
    mobileno: string;
}

export interface ICustomerFullResponse extends ICustomerModel {
  status: string;
}

export interface ICustomerParams {
    model: ICustomerModel;
    status?: string | number
}

export type ValidationObj = { key: string, value: string }[];

export interface ICustomerValidations {
    name: ValidationObj;
    company_name:ValidationObj;
    city: ValidationObj;
    mobileno: ValidationObj;
    status:ValidationObj
}

