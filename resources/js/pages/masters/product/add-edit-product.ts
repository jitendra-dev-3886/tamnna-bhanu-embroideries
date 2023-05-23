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
import {
    ICategoryFullResponse,
    ICategoryLightResponse,
} from "../../../../assets/types/category";
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
    setunit = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    errorMessage = "";
    totalPrice: number = 0;
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

        /* price: [
            {
                key: "required",
                value: "Price required",
            },
            {
                key: "numeric",
                value: "Enter valid number for price of the product",
            },
        ],*/
        unit_price: [
            {
                key: "required",
                value: "Unit Price required",
            },
            {
                key: "numeric",
                value: "Enter valid number for unit price of the product",
            },
        ],
        set_unit: [
            {
                key: "required",
                value: "Set Unit required",
            },
            {
                key: "numeric",
                value: "Enter a number from 1 to 10 for set unit of the product",
            },
        ],
        available_color: [
            {
                key: "required",
                value: "Available Color required",
            },
        ],
        description: [
            {
                key: "required",
                value: "Remarks required",
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

        parent_id: [
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
            {
                key: "min_value",
                value: "Stock should have proper value",
            },
        ],

        featured_image: [
            {
                key: "required",
                value: "Feature Image required",
            },
            {
                key: "ext",
                value: "Extension: jpeg, png or jpg are only accepted",
            },
            {
                key: "size",
                value: "Maximum size allowed is 5 MB",
            },
        ],

        product_galleries: [
            {
                key: "counter",
                value: "You can upload only 5 images in the gallery",
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
                value: "Maximum you can upload 5 files  ",
            },
            {
                key: "size",
                value: "Maximum size allowed is 20 MB",
            },
        ],
    };

    isSubmitting = false;

    isDataLoading = false;
    subCategoryList: ICategoryFullResponse[] = [];
    get model(): IProductModel {
        return ProductModule.model;
    }

    get isEditMode(): boolean {
        return ProductModule.editId ? <number>ProductModule.editId > 0 : false;
    }

    // get categoryList(): ICategoryLightResponse[] {
    //     return CategoryModule.categoryList;
    // }

    get parentCategoryList(): ICategoryFullResponse[] {
        return CategoryModule.parentCategoryList;
    }

    //Methods

    /**
     * Register Submit Method - Form Data
     */

    get calcTotalPrice() {
        this.totalPrice =
            Number(this.model.set_unit) * Number(this.model.unit_price);
        return this.totalPrice;
    }

    onSubmit(): void {
        this.$validator.validate().then((valid) => {
            const self = this;
            if (valid) {
                self.isSubmitting = true;
                this.errorMessage = "";

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
                formData.append("available_color", self.model.available_color);
                formData.append("set_unit", self.model.set_unit);
                formData.append("unit_price", self.model.unit_price);
                formData.append(
                    "stock",
                    self.model.available_status == "1" ? self.model.stock : "0"
                );

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
                } else {
                    apiName = "edit";
                    editId = <number>ProductModule.editId;
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
    setDescription(): void {
        this.model.description = "";

        if (this.model.parent_id.length > 0) {
            if (this.model.category_id.length > 0) {
                this.model.description = "";
                this.model.parent_id.forEach((element) => {
                    let filteredTempCatList = this.subCategoryList.filter(
                        (subCatElement) => subCatElement.parent_id == element
                    );
                    if (filteredTempCatList.length > 0) {
                        this.setDynamicDescription(
                            filteredTempCatList,
                            this.model.category_id
                        );
                    } else {
                        let filteredTempParentCatList =
                            this.parentCategoryList.filter(
                                (parCatElement) => parCatElement.id == element
                            );
                        this.setDynamicDescription(
                            filteredTempParentCatList,
                            this.model.parent_id
                        );
                    }
                });
            } else {
                this.model.description = "";
                this.setDynamicDescription(
                    this.parentCategoryList,
                    this.model.parent_id
                );
            }
        }
    }

    setDynamicDescription(
        tempArr: ICategoryFullResponse[],
        tempCategoryId: string[]
    ): void {
        tempArr.forEach((element) => {
            if (tempCategoryId.includes(element.id)) {
                var parser = new DOMParser();
                let doc =
                    `<b>${element.name}</b> :` +
                    parser
                        .parseFromString(element.description, "text/html")
                        .body.innerHTML.toString();
                let doc1 = parser.parseFromString(
                    this.model.description,
                    "text/html"
                );

                this.model.description = `<p>${doc1.body.innerHTML.toString()} ${doc}</p>`;
            }
        });
    }

    setStock(): void {
        this.model.stock =
            this.model.available_status == "0" ? "0" : this.model.stock;
    }
    /* Cancel */
    onCancel(): void {
        this.onModalClear("product", "CLEAR_STORE");
        this["$router"].push("/masters/product");
    }

    setSubCategoryList(): void {
        //let payload = [];
        if (this.model.parent_id.length > 0) {
            // CategoryModule.getSubCategoryList(this.model.parent_id).then(
            //     (
            //         response: AxiosResponse<
            //             ResponseResult<ICategoryFullResponse[]>
            //         >
            //     ) => {
            //         // CategoryModule.SET_PARENT_CATEGORY_LIST(
            //         //     response.data.data as ICategoryFullResponse[]
            //         // );
            //         this.subCategoryList = response.data
            //             .data as ICategoryFullResponse[];
            //         this.setDescription();
            //         HTMLClassModule.removeBodyClassName("page-loading");
            //     },
            //     (error) => {
            //         HTMLClassModule.removeBodyClassName("page-loading");
            //         this.showDialog(this.getAPIErrorMessage(error.response));
            //     }
            // );
            this.subCategoryList = [
                {
                    id: "4",
                    parent_id: "3",
                    name: "bandhni print",
                    description:
                        "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>Silk bandhni print</p>\r\n</body>\r\n</html>",
                    featured_image: "http://127.0.0.1:8000/images/default.png",
                },
                {
                    id: "5",
                    parent_id: "3",
                    name: "polka dots",
                    description:
                        "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>Silk polka dots&nbsp;</p>\r\n</body>\r\n</html>",
                    featured_image: "http://127.0.0.1:8000/images/default.png",
                },
            ];
            this.setDescription();
        }
    }

    getParentCategoryList(): void {
        HTMLClassModule.addBodyClassName("page-loading");
        CategoryModule.getParentCategoryList().then(
            (
                response: AxiosResponse<ResponseResult<ICategoryFullResponse[]>>
            ) => {
                CategoryModule.SET_PARENT_CATEGORY_LIST(
                    response.data.data as ICategoryFullResponse[]
                );
                HTMLClassModule.removeBodyClassName("page-loading");
            },
            (error) => {
                HTMLClassModule.removeBodyClassName("page-loading");
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }

    created(): void {
        this.getParentCategoryList();
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

                        parent_id: castedProductResponse.data?.data?.parent_id,
                        available_status:
                            castedProductResponse.data?.data?.available_status,
                        stock: castedProductResponse.data?.data?.stock,
                        featured_image:
                            castedProductResponse.data?.data?.featured_image,
                        product_galleries: [],
                        status: castedProductResponse.data?.data?.status,
                        available_color:
                            castedProductResponse.data?.data?.available_color,
                        set_unit: castedProductResponse.data?.data?.set_unit,
                        unit_price:
                            castedProductResponse.data?.data?.unit_price,
                    };

                    ProductModule.SET_MODEL(productModel);
                }
            },
            (error) => {
                this.showDialog(this.getAPIErrorMessage(error.response));
            }
        );
    }
}

export default AddEditProduct;
