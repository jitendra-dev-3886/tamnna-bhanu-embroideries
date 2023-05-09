import { Component, Mixins } from "vue-property-decorator";
import CommonServices from "../../../mixins/common";
import CommonApis from "../../../mixins/common-apis";
import ErrorBlockServer from "../../../components/ErrorBlockServer.vue";
import { HTMLClassModule } from "../../../store/htmlclass";
import { ProductModule } from "../../../store/product";
import { SnackbarModule } from "../../../store/snackbar";

import {
    IProductModel,
    IProductFullResponse,
    IProductValidations,
} from "../../../../assets/types/product";

import { CategoryModule } from "@/store/category";
import { ICategoryLightResponse } from "../../../../assets/types/category";
import { CommonModule } from "@/store/common";
import { AxiosResponse } from "axios";

import { ResponseResult } from "../../../../assets/types/common";

@Component({
    components: {
        ErrorBlockServer,
    },
})
class AddEditProduct extends Mixins(CommonServices, CommonApis) {
    errorDialog = false;

    errorMessage = "";

    validationMessages: IProductValidations = {
        name: [
            {
                key: "required",
                value: "Name required",
            },
            {
                key: "max",
                value: "Maximum length should be 191",
            },
        ],

        price: [
            {
                key: "required",
                value: "Price required",
            },
            {
                key: "numeric",
                value: "Enter valid number for price of the product",
            },
        ],

        description: [
            {
                key: "required",
                value: "Description required",
            },
        ],

        item_code: [
            {
                key: "required",
                value: "Item Code required",
            },
            {
                key: "max",
                value: "Maximum length should be 191",
            },
        ],

        category_id: [
            {
                key: "required",
                value: "Category required",
            },
        ],

        available_status: [
            {
                key: "required",
                value: "Availability required",
            },
        ],

        stock: [
            {
                key: "required",
                value: "Available Number of Stock required",
            },
            {
                key: "numeric",
                value: "Enter valid number for stock available",
            },
        ],

        featured_image: [
            {
                key: "required",
                value: "Featured Image required",
            },
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "size",
                value: "Maximum size allowed is 1 MB",
            },
        ],

        product_galleries: [
            {
                key: "required",
                value: "Product Gallery required",
            },
            {
                key: "length",
                value: "Maximum file upload limit is 5",
            },
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "valid_file_length",
                value: "Maximum file upload limit is ",
            },
            {
                key: "size",
                value: "Maximum size allowed is 5 MB",
            },
        ],
    };

    isSubmitting = false;

    isDataLoading = false;

    get model(): IProductModel {
        return ProductModule.model;
    }

    get isEditMode(): boolean {
        return ProductModule.editId ? <number>ProductModule.editId > 0 : false;
    }

    get categoryList(): ICategoryLightResponse[] {
        return CategoryModule.categoryList;
    }

    //Methods

    /**
     * Register Submit Method - Form Data
     */
    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                let apiName = "create";
                let editId: string | number = "";

                const formData = new FormData();

                formData.append("name", self.model.name);
                formData.append("price", self.model.price);
                formData.append("description", self.model.description);
                formData.append("item_code", self.model.item_code);
                formData.append(
                    "available_status",
                    self.model.available_status
                );
                formData.append("stock", self.model.stock);

                //Multiple Categories Array
                if (
                    self.model.category_id &&
                    self.model.category_id.length > 0
                ) {
                    Array.from(self.model.category_id).forEach(
                        (category_id) => {
                            formData.append(
                                "category_id[]",
                                <string>category_id
                            );
                        }
                    );
                }

                if (!this.isEditMode) {
                    formData.append(
                        "featured_image",
                        self.model.featured_image
                    );

                    // Multiple Product Gallery array
                    if (
                        self.model.product_galleries &&
                        self.model.product_galleries.length > 0
                    ) {
                        Array.from(self.model.product_galleries).forEach(
                            (product_galleries) => {
                                formData.append(
                                    "product_galleries[]",
                                    <Blob>product_galleries
                                );
                            }
                        );
                    }
                }

                // For Edit Product
                if (ProductModule.editId && <number>ProductModule.editId > 0) {
                    apiName = "edit";
                    editId = ProductModule.editId;
                } else {
                }

                ProductModule[apiName]({ model: formData, editId }).then(
                    (
                        response: AxiosResponse<
                            ResponseResult<IProductFullResponse>
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
        this.onModalClear("product", "CLEAR_STORE");
        this["$router"].push("/masters/product");
    }

    /**
     * When Single Master call is needed
     */
    commonDataFetch(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        this["$validator"].reset();
        this.isDataLoading = true;
        CommonModule.getAll({
            apiName: "categories",
            pagination: { page: 1 },
        }).then(
            (response: AxiosResponse<ResponseResult<unknown>>) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                CategoryModule.SET_CATEGORY_LIST(
                    <ICategoryLightResponse[]>response.data.data
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

    created(): void {
        this.resetStoreData("product").then(
            (response: unknown) => {
                const castedProductResponse = response as AxiosResponse<
                    ResponseResult<IProductFullResponse>
                >;
                if (castedProductResponse?.data?.data) {
                    const productModel: IProductModel = {
                        name: castedProductResponse.data?.data?.name,
                        price: castedProductResponse.data?.data?.price,
                        description:
                            castedProductResponse.data?.data?.description,
                        item_code: castedProductResponse.data?.data?.item_code,
                        category_id:
                            castedProductResponse.data?.data?.category_id,
                        available_status:
                            castedProductResponse.data?.data?.available_status,
                        stock: castedProductResponse.data?.data?.stock,
                        featured_image:
                            castedProductResponse.data?.data?.featured_image,
                        product_galleries: [],
                    };

                    ProductModule.SET_MODEL(productModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );

        if (this.done24Hours("add-edit-product-call")) {
            this.commonDataFetch();
        }
    }
}

export default AddEditProduct;
