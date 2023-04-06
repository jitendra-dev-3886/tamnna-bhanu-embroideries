import CommonServices from "@/mixins/common";
import ObjectModal from "./ObjectModal.vue";
import TableModal from "./TableModal.vue";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { ActivityLogModule } from "@/store/activity-log";

@Component({
    components: {
        ObjectModal,
        TableModal,
    },
})
class ActivityLogViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;
    @Prop({ default: {} }) modelObj!: object;

    objectModalView: boolean = false;
    tableModalView: boolean = false;

    get model() {
        return ActivityLogModule.model;
    }

    openTableModal(val, prop) {
        ActivityLogModule.SET_TABLE_MODAL_DATA({ tableData: val, col: prop });
        this.tableModalView = true;
    }

    openObjectModal(val, prop) {
        ActivityLogModule.SET_OBJECT_MODAL_DATA({ modelData: val, col: prop });
        this.objectModalView = true;
    }

    onCancel() {
        ActivityLogModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }

    checkToShowPropertyOrNot(value, prop) {
        return !(
            prop.indexOf("_id") !== -1 ||
            prop == "created_at" ||
            prop == "updated_at" ||
            prop == "deleted_at" ||
            prop == "created_by" ||
            prop == "updated_by" ||
            prop == "deleted_by" ||
            prop == "pivot"
        );
    }
}

export default ActivityLogViewModal;
