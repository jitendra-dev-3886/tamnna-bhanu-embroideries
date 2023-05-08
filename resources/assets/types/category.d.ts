export interface ICategoryModel {
    name: string;
    description: string;
    featured_image: File | Blob | string;
}

export interface ICategoryUpdatePayload {
    name: string;
    description: string;
}

export interface ICategoryParams {
    model: ICategoryModel;
    editId?: string | number;
}

export interface ICategoryLightResponse {
    id: string;
    name: string;
    description: string;
    featured_image: File | Blob | string;
}

export interface ICategoryFullResponse extends ICategoryLightResponse {}

export type ValidationObj = { key: string; value: string }[];

export interface ICategoryValidations {
    name: ValidationObj;
    description: ValidationObj;
    featured_image: ValidationObj;
}
