import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../../mixins/common";
import CommonApis from "../../../mixins/common-apis";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue"; 
import { HTMLClassModule } from "../../../store/htmlclass";
import { CartModule } from "../../../store/cart";
import { SnackbarModule } from "../../../store/snackbar";

import { 
    ICartBatchResponse,
    ICartModel,
    ICartFullResponse,
    ICartValidations,
} from "../../../../assets/types/cart";


import { UserModule } from "@/store/user";
import { IUserLightResponse } from "../../../../assets/types/user";
import { ProductModule } from "@/store/product";
import { IProductLightResponse } from "../../../../assets/types/product";
import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";

import { BatchRequestModule } from "../../../store/batch-request";

import {
    ResponseResult, 
    IBatchReqUrls,
} from "../../../../assets/types/common";



@Component({
    components: {
        ErrorBlockServer,
        
    },
})
class AddEditCart extends Mixins(CommonServices, CommonApis) {
    errorDialog = false;

    errorMessage = "";

    validationMessages: ICartValidations = { 
            
                user_id: [
                    {
                        key: 'required',
                        value: 'User required'
                    },
                ],
            
                product_id: [
                    {
                        key: 'required',
                        value: 'Product required'
                    },
                ],
            
                quantity: [
                    {
                        key: 'required',
                        value: 'Quantity required'
                    },
                ],
    };

    isSubmitting = false;
     
    isBatchRequestLoading= false;

    get model(): ICartModel {
        return CartModule.model;
    }

    get isEditMode(): boolean {
        return CartModule.editId ? CartModule.editId > 0 : false;
    }
    
    get userList(): IUserLightResponse[] {
        return UserModule.userList;
    }
    get productList(): IProductLightResponse[] {
        return ProductModule.productList;
    }
    

    //Methods
    
         /* JSON Form Submit - Start*/
    onSubmit(): void{
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                // For Edit Cart
                if (CartModule.editId && CartModule.editId > 0) {
                    apiName = "edit";
                    editId = CartModule.editId;
                }

                //To Submit JSON Request
                const sendParamModel = JSON.parse(JSON.stringify(self.model));

                
            
            
            

                CartModule[apiName]({ model: sendParamModel, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<ICartFullResponse>
                        >
                    ) => {
                        this.onCancel();
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
    /* JSON Form Submit - End*/

    /* Cancel */
    onCancel(): void {
        this.onModalClear("cart", "CLEAR_STORE");
        this["$router"].push("/masters/cart");
    }

    
     /**
     * If batch request then call this method
     */
    commonBatchRequest(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        this.$validator.reset();
        this.isBatchRequestLoading = true;
        /**
         * Batch request (Max 10 request is allowed at a time)
         */
        const api = "getBatchRequests";
        const requestArray: IBatchReqUrls[] = [ 
            {
                url: "api/v1/users?is_light=1",
                request_id: "userList",
            },
            {
                url: "api/v1/products?is_light=1",
                request_id: "productList",
            },
        ];

        BatchRequestModule[api](requestArray).then(
            (response: unknown) => {
                const castedResponse = response as ICartBatchResponse;
                this.isBatchRequestLoading = false;
                HTMLClassModule.removeBodyClassName("page-loading");
                
                UserModule.SET_USER_LIST(
                    castedResponse.userList.data as IUserLightResponse[]
                );
                ProductModule.SET_PRODUCT_LIST(
                    castedResponse.productList.data as IProductLightResponse[]
                );
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.isBatchRequestLoading = false;
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    created(): void {

        this.resetStoreData("cart").then(
            (response: unknown) => {
                const castedCartResponse = response as AxiosResponse<
                    ResponseResult<ICartFullResponse>
                >;
                if (castedCartResponse?.data?.data) {

                    

                    const cartModel: ICartModel = {
                        user_id: castedCartResponse.data?.data?.user_id, 
            product_id: castedCartResponse.data?.data?.product_id, 
            quantity: castedCartResponse.data?.data?.quantity,
                    };
                    
                    CartModule.SET_MODEL(cartModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

        
         if (this.done24Hours('add-edit-cart-call')) {
             this.commonBatchRequest();
         }
    }
}

export default AddEditCart;
