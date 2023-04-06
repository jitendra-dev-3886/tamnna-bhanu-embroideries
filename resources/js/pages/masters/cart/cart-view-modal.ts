import CommonServices from "@/mixins/common";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { CartModule } from "@/store/cart";
import { ICartFullResponse } from "../../../../assets/types/cart";
@Component
class CartViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    get model(): ICartFullResponse {
        return CartModule.viewModel;
    }

    onCancel(): void {
        CartModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }
}
export default CartViewModal;
