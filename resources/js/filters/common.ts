export function isExistInLocalStorage(storeName: string): boolean {
    const store = localStorage.getItem("tamanna-bhanu-embroideries-b2b-mcommerce");
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
