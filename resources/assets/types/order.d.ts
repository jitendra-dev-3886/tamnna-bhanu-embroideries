
import {IUserFullResponse, IUserLightResponse} from "./user";
export interface IOrderModel {
    user_id: string;
    quantity: string;
    gst: string;
    payment_amount: string;
    order_status: string;
    order_status_remark: string;
    user_remark: string;
    order_products: Blob[];
}

export interface IOrderParams {
    model: IOrderModel;
    editId?: string|number
}

export interface IOrderLightResponse {
    id: string;
    user_id: string;
}

export interface IOrderFullResponse extends IOrderLightResponse{
    quantity: string;
    gst: string;
    payment_amount: string;
    order_status: string;
    order_status_text: string;
    order_status_remark: string;
    user_remark: string;
    order_products: IOrderProducts[];
}

export type ValidationObj = { key: string, value: string }[];

export interface IOrderValidations {
    user_id: ValidationObj;
    quantity: ValidationObj;
    gst: ValidationObj;
    payment_amount: ValidationObj;
    order_status: ValidationObj;
    order_status_remark: ValidationObj;
    user_remark: ValidationObj;
    order_products: ValidationObj;
}



export interface CommonFileResponse {
    id: string;
    Order_id: string;
}

export interface IOrderProducts extends CommonFileResponse{
    product_id: string;
    product_id_original: string;
    product_id_thumbnail: string;
}
                                    