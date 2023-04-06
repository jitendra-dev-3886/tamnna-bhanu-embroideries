/* eslint-disable @typescript-eslint/no-explicit-any */
import DeleteModal from "@/components/DeleteModal.vue";
import ExportBtn from "@/components/ExportBtn.vue";
import UserViewModal from "./UserViewModal.vue";
import MultiDelete from "@/components/MultiDelete.vue";
import Component, { mixins } from "vue-class-component";
import {
    IUserFullResponse,
} from "../../../../assets/types/user";
import { UserModule } from "@/store/user";
import { CommonModule } from "@/store/common";
import { HTMLClassModule } from "@/store/htmlclass";
import { RoleModule } from "@/store/role";
import { IRoleLightResponse } from "../../../../assets/types/role";
import ColumnVisibilityBtn from "@/components/ColumnVisibilityBtn.vue";
import Import from "@/components/Import.vue";
import CommonApis from "@/mixins/common-apis";

import {
    IExportProps,
    IParamProps,
    IConfirmationProps,
    IDeleteProps,
    IImportProps,
    ResponseResult,
} from "../../../../assets/types/common";

import { AxiosResponse } from "axios";
import ServerTable from "@/mixins/customtable/server-table";

import {
    ITableHeaders,
    IFilterModel,
} from "../../../../assets/types/table";

@Component({
    components: {
        ExportBtn,
        UserViewModal,
        DeleteModal,
        MultiDelete,
        ColumnVisibilityBtn,
        Import,
    },
})
class UserList extends mixins(ServerTable, CommonApis) {
    tab = "tab1";
    modalOpen = false;
    isImportLoaded = false;
    urlApi = "users";

    headers: ITableHeaders[] =
          [
             { text: 'Email', value: 'email' },
             { text: 'Role', value: 'role_id' },
             { text: 'Actions', value: 'actions', sortable: false },
          ]

    confirmation: IConfirmationProps = {
        title: "",
        description: "",
        btnCancelText: this.$getConst("BTN_CANCEL"),
        btnConfirmationText: this.$getConst("BTN_OK"),
    };
    deleteProps: IDeleteProps = {
        ids: "",
        store: "",
    };
    exportProps: IExportProps = {
        ids: "",
        fileName: "User",
        apiName: "users-export",
    };
    importProps: IImportProps = {
        store: "user",
        modelName: "user",
        multiple: false,
    };

    paramProps: IParamProps = {
        idProps: "",
        storeProps: "",
    };
    role_id="";
    filterMenu= false;
    filterModel: IFilterModel = {};


    userViewModal = false;

    //Computed Properties


    get roleList(): IRoleLightResponse[] {
        return RoleModule.roleList;
    }


    // Methods

    /**
     * Redirect to add user page
     * */
    addUser(): void {
        this.$router.push("/users/add");
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
     * Edit User
     * */
    onEdit(id: string | number): void {
        UserModule.SET_EDIT_ID(id);
        this.$router.push("/users/edit/" + id);
    }

    /**
     * View User
     * @param id
     */
    onView(id: string | number): void {
        HTMLClassModule.addBodyClassName("page-loading");
        UserModule.getById(id).then(
            (response: AxiosResponse<ResponseResult<IUserFullResponse>>) => {
                UserModule.SET_VIEW_MODEL(response.data.data as IUserFullResponse);
                HTMLClassModule.removeBodyClassName("page-loading");
                this.userViewModal = true;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    /**
     * delete user
     * @param id
     */
    deleteItem(id: string | number): void {
        this.paramProps.idProps = id;
        this.paramProps.storeProps = "user";
        this.confirmation.title = this.$getConst("DELETE_TITLE");
        this.confirmation.description = this.$getConst("WARNING");
        this.modalOpen = true;
    }

    /**
     * Multiple Delete
     */
    multipleDelete(): void {
        const rowIds: string[] = [];
        let isSuperadminIncluded = false;
        this.selected.forEach((element) => {
            if (element.role_id == "1" && element.id == "1") {
                isSuperadminIncluded = true;
            } else {
                rowIds.push(element.id);
            }
        });
        if (isSuperadminIncluded) {
            this.showDialog(this.$getConst("SUPER_ADMIN_DELETE_WARNING"));
        } else {
            this.deleteProps.ids = rowIds;
            this.deleteProps.store = "user";
            (<any>this.$refs.multipleDeleteBtn).deleteMulti();
        }
    }


    /**
     * Change Filter
     */
    changeFilter() {
       const filter: IFilterModel = {};

       if (this.role_id != '') {
         filter.role_id = [this.role_id];
       }

       this.filterModel = filter;
       this.refresh();
       this.filterMenu = false;
     }

    /**
     * Reset Filter
     */
    resetFilter(): void {
      this.role_id = "";
        this.changeFilter();
     }

    refreshData(): void {
        const self = this;
        setTimeout(function () {
            if (self.tab == "tab1") {
                self.refresh();
            } else if (self.tab == "tab2" && self.$refs.importdata) {
                if (self.isImportLoaded) {
                    (self.$refs.importdata as any).refreshImport();
                }
                self.isImportLoaded = true;
            }
        }, 100);
    }




    created(): void {

        HTMLClassModule.addBodyClassName("page-loading");

        /*Single Master : Start */
        CommonModule.getAll({
            apiName: "roles",
            pagination: { page: 1 },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                RoleModule.SET_ROLE_LIST(
                    <IRoleLightResponse[]>response.data.data
                );
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

      /*Single Master : End */
    }
}

export default UserList;
