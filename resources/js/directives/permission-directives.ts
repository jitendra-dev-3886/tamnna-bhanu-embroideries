import { DirectiveOptions } from "vue";
import { isPermission } from "../filters/permission-filters";
import { PermissionModule } from "@/store/permission";

const hasPermission: DirectiveOptions = {
    bind(el, { name, value }) {
        
        if (name == "canAccess") {
            name = "show";
        }
        // eslint-disable-next-line max-len
        const hasInitialPermission = isPermission(
            PermissionModule.userPermissions,
            value,
            name
        );
        if (!hasInitialPermission) {
            el.style.display = "none";
        }
    },
    update(el, { name, value }) {
        if (name == "canAccess") {
            name = "show";
        }
        // eslint-disable-next-line max-len
        const hasUpdatedPermission = isPermission(
            PermissionModule.userPermissions,
            value,
            name
        );
        if (!hasUpdatedPermission) {
            el.style.display = "none";
        } else if (
            el.localName == "span" ||
            el.localName == "button" ||
            el.localName == "i"
        ) {
            el.style.display = "inline-block";
        } else if (el.localName == "a") {
            el.style.display = "inline";
        } else {
            el.style.display = "block";
        }
    },
};

export default hasPermission;
