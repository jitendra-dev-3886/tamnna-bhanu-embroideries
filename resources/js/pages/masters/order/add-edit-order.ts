import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../../mixins/common";
import CommonApis from "../../../mixins/common-apis";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import { OrderModule } from "../../../store/order";
import { SnackbarModule } from "../../../store/snackbar";

import {
    IOrderModel,
    IOrderFullResponse,
    IOrderValidations,
} from "../../../../assets/types/order";


import { UserModule } from "@/store/user";
import { IUserLightResponse } from "../../../../assets/types/user";
import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";


import {
    ResponseResult,
} from "../../../../assets/types/common";
import { IProductFullResponse } from "../../../../assets/types/product";



@Component({
    components: {
        ErrorBlockServer,

    },
})
class AddEditOrder extends Mixins(CommonServices, CommonApis) {
    errorDialog = false;

    errorMessage = "";

    validationMessages: IOrderValidations = {

                user_id: [
                    {
                        key: 'required',
                        value: 'User required'
                    },
                ],

                quantity: [
                ],

                gst: [
                ],

                payment_amount: [
                ],

                order_status: [
                    {
                        key: 'required',
                        value: 'Order Status required'
                    },
                ],

                order_status_remark: [
                ],

                user_remark: [
                ],

                order_products: [
                ],
    };

    isSubmitting = false;

    isDataLoading= false;

    get model(): IOrderModel {
        return OrderModule.model;
    }

    get isEditMode(): boolean {
        return OrderModule.editId ? OrderModule.editId > 0 : false;
    }

    get userList(): IUserLightResponse[] {
        return UserModule.userList;
    }


    //Methods

    /**
     * Register Submit Method - Form Data
     */
    onSubmit(): void{
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                const formData = new FormData();


            formData.append('user_id', self.model.user_id);
            formData.append('quantity', self.model.quantity);
            formData.append('gst', self.model.gst);
            formData.append('payment_amount', self.model.payment_amount);
            formData.append('order_status', self.model.order_status);
            formData.append('order_status_remark', self.model.order_status_remark);
            formData.append('user_remark', self.model.user_remark);

                // Multiple Order Product array
               /* if (
                    self.model.order_products &&
                    self.model.order_products.length > 0
                ) {
                    Array.from(self.model.order_products).forEach(
                        (order_products) => {
                            formData.append(
                                "order_products[]",
                                <Blob>order_products
                            );
                        }
                    );
                }*/

                // For Edit Order
                if (OrderModule.editId && OrderModule.editId > 0) {
                    apiName = "edit";
                    editId = OrderModule.editId;
                } else {

                }

                OrderModule[apiName]({ model: formData, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IOrderFullResponse>
                        >
                    ) => {
                        this.onCancel();
                        // Success message
                        SnackbarModule.setMsg(response.data.message as string);
                    },
                    (error) => {
                        self.isSubmitting = false;
                        self.errorMessage = self.getAPIErrorMessage(
                            error.response
                        );
                    }
                );
            }
        });
    }

    /* Cancel */
    onCancel(): void {
        this.onModalClear("order", "CLEAR_STORE");
        this["$router"].push("/masters/order");
    }




     /**
     * When Single Master call is needed
     */
    commonDataFetch(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        this["$validator"].reset();
        this.isDataLoading = true;
        CommonModule.getAll({
            apiName: "users",
            pagination: { isLight: true },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                UserModule.SET_USER_LIST(
                    <IUserLightResponse[]>response.data.data
                );
                this.isDataLoading = false;
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.isDataLoading = false;
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    /*created(): void {

        this.resetStoreData("order").then(
            (response: unknown) => {
                const castedOrderResponse = response as AxiosResponse<
                    ResponseResult<IOrderFullResponse>
                >;
                if (castedOrderResponse?.data?.data) {



                    const orderModel: IOrderModel = {
                        user_id: castedOrderResponse.data?.data?.user_id,
                        user_name: castedOrderResponse.data?.data?.user_name,
            quantity: castedOrderResponse.data?.data?.quantity,
            gst: castedOrderResponse.data?.data?.gst,
            payment_amount: castedOrderResponse.data?.data?.payment_amount,
            order_status: castedOrderResponse.data?.data?.order_status,
            order_status_remark: castedOrderResponse.data?.data?.order_status_remark,
            user_remark: castedOrderResponse.data?.data?.user_remark,
            id:castedOrderResponse.data?.data?.id,
            order_status_text:castedOrderResponse.data?.data?.order_status_text,
            order_products: {
                product:{
                name:"",
                quantity:"",
                price:""
                },
                categories:{
                    id:"",
                    name:"",
                    featured_image:""
                }
            }
                    };

                    OrderModule.SET_MODEL(orderModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );


        if (this.done24Hours('add-edit-order-call')) {
            this.commonDataFetch();
        }
    }*/
}

export default AddEditOrder;
