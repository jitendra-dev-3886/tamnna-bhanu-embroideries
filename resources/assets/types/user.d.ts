import {IPermissionResponse} from "./permission";
import {IRoleFullResponse, IRoleLightResponse} from "./role";

export interface IUserModel {
    email: string;
    contact_number:string;
    password: string;
    role_id: string;
    name:string;
    company_name:string;
    city:string;
}

export interface IUserParams {
    model: IUserModel;
    editId?: string | number
}

export type ValidationObj = { key: string, value: string }[];

export interface IUserValidations {
    email: ValidationObj;
    password: ValidationObj;
    role_id: ValidationObj;
    name:ValidationObj;
    company_name:ValidationObj;
    city: ValidationObj;
}

export interface IUserLightResponse {
    id: string;
    email: string;
    name:string;
    contact_number:string
}

export interface IUserFullResponse extends IUserLightResponse{

    email_verified_at: string;
    role_id: string;
    role: {
        guard_name: null | string;
        id: string;
        landing_page: null | string;
        name: string;
    }
}

export interface ICurrentUserData extends IUserFullResponse{
    authorization: string;
    profile:string;
    permissions: IPermissionResponse[];
    sample_excels:{
        sample_user: string;
        sample_category: string;
        sample_product: string;
        sample_order: string;
        sample_cart: string;
    }[];
}

export interface IUserCheckEmail {
    id: string | number,
    email: string
}


