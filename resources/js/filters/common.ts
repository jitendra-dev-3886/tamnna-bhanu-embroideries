import { IMenuItem } from "../../assets/types/common";

export function isExistInLocalStorage(storeName: string): boolean {
    const store = localStorage.getItem(
        "tamanna-bhanu-embroideries-b2b-mcommerce"
    );
    if (store) {
        const parsedStore = JSON.parse(store);
        if (Object.prototype.hasOwnProperty.call(parsedStore, storeName)) {
            return true;
        }
    }
    return false;
}

export function getSortType(isDescending: string | boolean | undefined) {
    if (isDescending == true) {
        return "desc";
    } else if (isDescending == false) {
        return "asc";
    } else {
        return "";
    }
}

export const directiveList = [
    "store",
    "index",
    "canAccess",
    "update",
    "destroy",
    "export",
    "importBulk",
    "deleteGallery",
    "updateProductImage",
    "updateProductGallery",
    "deleteProductId",
    "getPermissionsByRole",
    "setUnsetPermissionToRole",
    "changePassword",
    "deleteAll",
    "updateCategoryImage",
    "updateBannerImage",
];

export const menuItems: IMenuItem[] = [
    {
        label: "Dashboard",
        url: "/dashboard",
        directiveName: "index",
        icon: "flaticon-dashboard",
        permissionName: "DASHBOARD",
        hasPermission: true,
        menuType: "single",
    },
    {
        label: "Users",
        url: "/users",
        directiveName: "index",
        icon: "flaticon-users",
        permissionName: "USERS",
        hasPermission: true,
        menuType: "single",
    },
    {
        label: "Masters",
        url: "/masters",
        directiveName: "index",
        icon: "flaticon2-digital-marketing",
        permissionName: "",
        hasPermission: true,
        menuType: "nested",
        matchUrl: "/masters",
        subMenus: [
            {
                label: "Home Banners",
                url: "/masters/homebanner",
                icon: "flaticon2-digital-marketing",
                directiveName: "index",
                permissionName: "HOMEBANNERS",
                menuType: "single",
                hasPermission: true,
            },
            {
                label: "Customers",
                url: "/masters/customer",
                icon: "flaticon2-digital-marketing",
                directiveName: "index",
                permissionName: "CUSTOMERS",
                menuType: "single",
                hasPermission: true,
            },
            {
                label: "Categories",
                url: "/masters/category",
                icon: "flaticon2-digital-marketing",
                directiveName: "index",
                permissionName: "CATEGORIES",
                menuType: "single",
                hasPermission: true,
            },
            {
                label: "Products",
                url: "/masters/product",
                icon: "flaticon2-digital-marketing",
                directiveName: "index",
                permissionName: "PRODUCTS",
                menuType: "single",
                hasPermission: true,
            },
            {
                label: "Orders",
                url: "/masters/order",
                icon: "flaticon2-digital-marketing",
                directiveName: "index",
                permissionName: "ORDERS",
                menuType: "single",
                hasPermission: true,
            },
        ],
    },
];
