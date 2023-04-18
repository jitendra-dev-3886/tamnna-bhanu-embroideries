import CommonServices from "@/mixins/common";
import { Component, Prop, Mixins } from "vue-property-decorator";
import { HomeBannerModule } from "@/store/homebanner";
import { IHomeBannerFullResponse } from "../../../../assets/types/homebanner";

@Component
class HomeBannerViewModal extends Mixins(CommonServices) {
    @Prop({ default: false }) value!: boolean;

    get model(): IHomeBannerFullResponse {
        return HomeBannerModule.viewModel;
    }

    onCancel(): void {
        HomeBannerModule.CLEAR_STORE();
        this.$emit("input"); //Close Pop-up
    }
}
export default HomeBannerViewModal;
