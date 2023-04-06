
import {IUserFullResponse, IUserLightResponse} from "./user";
import {IProductFullResponse, IProductLightResponse} from "./product";
import {ResponseResult} from "./common";

export interface ICartModel {
    user_id: string;
    product_id: string;
    quantity: string;
}

export interface ICartParams {
    model: ICartModel;
    editId?: string|number
}

export interface ICartLightResponse {
    id: string;
    user_id: string;
    product_id: string;
}

export interface ICartFullResponse extends ICartLightResponse{
    quantity: string;
}

export type ValidationObj = { key: string, value: string }[];

export interface ICartValidations {
    user_id: ValidationObj;
    product_id: ValidationObj;
    quantity: ValidationObj;
}


export interface ICartBatchResponse {
    userList: ResponseResult<IUserLightResponse[]>,
    productList: ResponseResult<IProductLightResponse[]>,
}

