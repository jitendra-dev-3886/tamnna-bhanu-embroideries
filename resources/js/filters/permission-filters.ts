import {
    IBindingValue,
    IPermissionResponse,
} from "../../assets/types/permission";

function getFilteredArray(arr: IPermissionResponse[], byFilterValue) {
    if (arr && arr.length > 0) {
        // eslint-disable-next-line max-len
        return arr
            .filter((item) => item.name == byFilterValue)
            .reduce((a, c) => Object.assign(a, c), Object.create(null));
    }
    return {};
}

export function isPermission(
    permissions: IPermissionResponse[],
    bindingValue: IBindingValue,
    permissionName: string
): boolean {
    if (bindingValue) {
        let module = getFilteredArray(permissions, bindingValue.module);
        if (module.is_third_level == "1") {
            module = getFilteredArray(
                module.sub_permissions,
                bindingValue.subModule
            );
        }
        const permissionObj = getFilteredArray(
            module.sub_permissions,
            permissionName
        );
        return permissionObj.is_permission == "1";
    }
    return true;
}
