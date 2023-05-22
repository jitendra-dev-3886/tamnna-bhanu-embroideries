
import { IUserLightResponse } from './user';
import { ICategoryFullResponse } from './category';

export interface IOrderProduct {
    featured_image: string;
    id: string;
    order_id: string;
    price: string;
    product: {
        name: string;
        item_code: string;
        description: string;
        available_status: string;
        created_at: string;
        stock: string;
        categories: ICategoryFullResponse[];
    };
    product_id: string;
    quantity: string;

}

/*export interface ICustomerDetail{
    name:string;
    email:string;
    contact_number:string;
    city:string
}*/
export interface IOrderModel {
    gst: string;
    id:string;

    order_products: IOrderProduct[];
    order_status: string;
    order_status_remark: string;
    order_status_text:string;
    payment_amount: string;
    quantity: string;
    user_id: string;
    user_remark: string;
    user:IUserLightResponse;

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
    product_id:string;
    product_id_original: string;
    product_id_thumbnail: string;
    quantity: string;
    created_at:string;
    gst: string;
    payment_amount: string;
    order_status: string;
    order_status_text: string;
    order_status_remark: string;
    user_name:string;
    user_remark: string;

    order_products: IOrderProduct[];
    user:IUserLightResponse;
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


