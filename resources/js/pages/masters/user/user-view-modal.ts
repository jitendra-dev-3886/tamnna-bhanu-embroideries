import CommonServices from "@/mixins/common";
import { Component, Mixins, Prop } from "vue-property-decorator";
import { UserModule } from "@/store/user";
import { IUserFullResponse } from "../../../../assets/types/user";

@Component
class UserViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    onCancel(): void {
        UserModule.CLEAR_MODEL();
        this.$emit("input"); // Close Pop-up
    }

    get model(): IUserFullResponse {
        return UserModule.viewModel;
    }
}
export default UserViewModal;
