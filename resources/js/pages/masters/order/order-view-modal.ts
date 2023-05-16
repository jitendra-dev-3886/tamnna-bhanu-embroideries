import CommonServices from "@/mixins/common";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { OrderModule } from "@/store/order";
import { IOrderFullResponse,IOrderProducts } from "../../../../assets/types/order";
@Component
class OrderViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    get model(): IOrderFullResponse {
        return OrderModule.viewModel;
    }
    get prodDetailModel():IOrderProducts{
        return OrderModule.prodDetail;
    }
    onCancel(): void {
        OrderModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }
}
export default OrderViewModal;
