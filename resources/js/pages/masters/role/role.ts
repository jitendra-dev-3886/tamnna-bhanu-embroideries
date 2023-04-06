import ServerTable from "../../../mixins/customtable/server-table";
import DeleteModal from "../../../components/DeleteModal.vue";
import AddRole from "./AddRole.vue";
import ExportBtn from "../../../components/ExportBtn.vue";
import MultiDelete from "../../../components/MultiDelete.vue";
import Component from "vue-class-component";
import { RoleModule } from "../../../store/role";
import { HTMLClassModule } from "../../../store/htmlclass";
import {
    IConfirmationProps,
    IExportProps,
    IParamProps,
    IDeleteProps
} from "../../../../assets/types/common";
import { IRoleLightResponse } from "../../../../assets/types/role";
import { ITableHeaders } from "../../../../assets/types/table";

@Component({
    components: {
        ExportBtn,
        DeleteModal,
        MultiDelete,
        AddRole
    }
})
class Role extends ServerTable {
    tab = "tab1";
    addRoleModal = false;
    modalOpen = false;
    urlApi = "roles"; // set store name here to set/get pagination data and for access of actions/mutation via custom table
    headers: ITableHeaders[] = [
        { text: "Role", value: "name" },
        { text: "Actions", value: "actions", sortable: false }
    ];
    paramProps: IParamProps = {
        idProps: "",
        storeProps: ""
    };
    exportProps: IExportProps = {
        ids: "",
        fileName: "Role",
        apiName: "roles-export"
    };
    deleteProps: IDeleteProps = {
        ids: "",
        store: ""
    };
    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this["$getConst"]("BTN_CANCEL"),
        btnConfirmationText: this["$getConst"]("BTN_OK")
    };
    role_id: string | number = "";

    get roleList(): IRoleLightResponse[] {
        return RoleModule.roleList;
    }

    mounted(): void {
        // Listening events in country public channel
        window["Echo"].private("manage-role").listen("ManageRole", e => {
            this.refresh();
        });
    }

    /**
     * Export data in CSV file
     */
    setExport(): void {
        const rowIds: string[] = [];
        this.selected.forEach((element, index) => {
            rowIds[index] = element.id;
        });

        this.exportProps.ids = rowIds;        
        (<any>this.$refs.exportbtn).exportToCSV();
    }

    /**
     * Add Role Modal method
     */
    addrole(): void {
        this.addRoleModal = true;
    }

    /**
     * Edit Role Modal
     * @param id
     */
    editItem(id: string | number): void {
        HTMLClassModule.addBodyClassName("page-loading");
        // set the edit id in store
        RoleModule.SET_EDIT_ID(id);
        // get by id to open and edit the role of particular id
        RoleModule.getById().then(
            () => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.addRoleModal = true;
            },
            error => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    /**
     * Delete Data from row
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "role";
        this.confirmation.title = this["$getConst"]("DELETE_TITLE");
        this.confirmation.description = this["$getConst"]("WARNING");
        this.modalOpen = true;
    }

    /* After delete selected  should be empty */
    deleteSuccess(): void {
        this.selected = [];
        this.getData();
    }

    /**
     * Multiple Delete
     */
    multipleDelete(): void {
        const rowIds: string[] = [];
        let isAdminIncluded = false;
        this.selected.forEach(element => {
            if (element.id == "1") {
                isAdminIncluded = true;
            } else {
                rowIds.push(element.id);
            }
        });
        if (isAdminIncluded) {
            this.showDialog(this["$getConst"]("ADMIN_DELETE_WARNING"));
        } else {
            this.deleteProps.ids = rowIds;
            this.deleteProps.store = "role";
            (<any>this.$refs.multipleDeleteBtn).deleteMulti();
        }
    }

    beforeDestroy(): void {
        RoleModule.CLEAR_STORE();
    }
}

export default Role;
