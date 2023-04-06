
import {ICategoryFullResponse, ICategoryLightResponse} from "./category";
export interface IProductModel {
    name: string;
    price: string;
    description: string;
    item_code: string;
    category_id: string;
    available_status: string;
    stock: string;
    featured_image: string;
    product_galleries: Blob[];
}

export interface IProductParams {
    model: IProductModel;
    editId?: string|number
}

export interface IProductLightResponse {
    id: string;
    name: string;
    price: string;
    description: string;
    item_code: string;
    featured_image: string;
}

export interface IProductFullResponse extends IProductLightResponse{
    category_id: string;
    category: ICategoryFullResponse;
    available_status: string;
    available_status_text: string;
    stock: string;
    product_galleries: IProductGalleries[];
}

export type ValidationObj = { key: string, value: string }[];

export interface IProductValidations {
    name: ValidationObj;
    price: ValidationObj;
    description: ValidationObj;
    item_code: ValidationObj;
    category_id: ValidationObj;
    available_status: ValidationObj;
    stock: ValidationObj;
    featured_image: ValidationObj;
    product_galleries: ValidationObj;
}



export interface CommonFileResponse {
    id: string;
    Product_id: string;
}

export interface IProductGalleries extends CommonFileResponse{
    gallery: string;
    gallery_original: string;
    gallery_thumbnail: string;
}
                                    
