import { ICategoryFullResponse, ICategoryLightResponse } from "./category";
export interface IProductModel {
    available_status: string;
    category_id: string[];
    parent_id: string[];
    description: string;
    featured_image: string;
    item_code: string;
    name: string;
    price: string;
    product_galleries: Blob[];
    stock: string;
    status: string;
    available_color: string;
    set_unit: string;
    unit_price: string;
}

export interface IProductParams {
    model: IProductModel;
    editId?: string | number;
}

export interface IProductStatusParams {
    status: string;
    editId?: string | number;
}

export interface IProductGalleryParams {
    images: any;
    editId?: string | number;
}

export interface IProductLightResponse {
    id: string;
    name: string;
    price: string;
    description: string;
    item_code: string;
    featured_image: string;
}

export interface IProductFullResponse extends IProductLightResponse {
    category_id: string[];
    parent_id: string[];
    category: ICategoryFullResponse[];
    // parent_id: ICategoryFullResponse;
    product_galleries: IProductGalleries[];
    available_status: string;
    available_status_text: string;
    stock: string;
    status: string;
    status_text: string;
    available_color: string;
    set_unit: string;
    unit_price: string;
}

export type ValidationObj = { key: string; value: string }[];

export interface IProductValidations {
    name: ValidationObj;
    //  price: ValidationObj;
    description: ValidationObj;
    item_code: ValidationObj;
    parent_id: ValidationObj;
    available_status: ValidationObj;
    stock: ValidationObj;
    featured_image: ValidationObj;
    product_galleries: ValidationObj;
    set_unit: ValidationObj;
    unit_price: ValidationObj;
    available_color: ValidationObj;
}

export interface CommonFileResponse {
    id: string;
    Product_id: string;
}

export interface IProductGalleries extends CommonFileResponse {
    gallery: string;
    gallery_original: string;
    gallery_thumbnail: string;
}
