import CommonServices from "@/mixins/common";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { ProductModule } from "@/store/product";
import { IProductFullResponse } from "../../../../assets/types/product";
@Component
class ProductViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    get model(): IProductFullResponse {
        return ProductModule.viewModel;
    }

    onCancel(): void {
        ProductModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }
}
export default ProductViewModal;
