import CommonServices from "@/mixins/common";
import { Component, Prop, Mixins } from "vue-property-decorator";

import { CategoryModule } from "@/store/category";
import { ICategoryFullResponse } from "../../../../assets/types/category";

@Component
class CategoryViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    get model(): ICategoryFullResponse {
        return CategoryModule.viewModel;
    }
    get parentCategoryList(): ICategoryFullResponse[] {
        return CategoryModule.parentCategoryList;
    }

    onCancel(): void {
        CategoryModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }

}
export default CategoryViewModal;
