export interface IParams {
    model: IRoleModel;
    editId?: string|number
}
export type ValidationObj = { key: string, value: string }[];
export interface IValidations {
    role: ValidationObj
}

export interface IRoleModel {
    name: string;
}
export interface IRoleLightResponse {
    id: string;
    name: string;
}
export interface IRoleFullResponse extends IRoleLightResponse{
    guard_name: null | string;
    landing_page: null | string;
}
