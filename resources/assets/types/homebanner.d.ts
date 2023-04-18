
export interface IHomeBannerModel {
    name: string;
    banner_status: string;
    featured_image: File|Blob|string;

}

export interface IHomeBannerParams {
    model: IHomeBannerModel;
    editId?: string|number
}

export interface IHomeBannerLightResponse {
    id: string;
    name: string;
    banner_status: string;
    featured_image: File|Blob|string;
}

export interface IHomeBannerFullResponse extends IHomeBannerLightResponse{
}

export type ValidationObj = { key: string, value: string }[];

export interface IHomeBannerValidations {
    name: ValidationObj;
    banner_status: ValidationObj;
    featured_image: ValidationObj;
}



