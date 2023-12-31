export type ValidationObj = { key: string, value: string }[];

export interface IForgotPasswordValidations {
    email?: ValidationObj;
    contact_number?:ValidationObj;
}

export interface ILoginValidations extends IForgotPasswordValidations{
    password: ValidationObj;
}

export interface IForgotPasswordModel{
    email: string;
}

export interface ILoginModel extends IForgotPasswordModel{
    contact_number:String,
    password: string;
    g_recaptcha_response: string;
}
export interface ICheckLoginModel extends IForgotPasswordModel {
    password: string;
}

export interface IChangePasswordModel {
    old_password: string;
    new_password: string;
    confirm_password: string;
}

export interface IChangePasswordValidations {
    old_password: ValidationObj;
    new_password: ValidationObj;
    confirm_password: ValidationObj;
}
