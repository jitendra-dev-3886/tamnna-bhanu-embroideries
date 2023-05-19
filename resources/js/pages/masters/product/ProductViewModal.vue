<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>View Product</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <table class="table table-striped mx-0 px-0">
                    <tbody>
                        <tr>
                            <td>
                                <h5
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Name:
                                </h5>
                            </td>
                            <td>
                                <h5
                                    class="font-weight-regular font-size-h3-sm"
                                    style="color: teal"
                                >
                                    {{ model.name != "" ? model.name : "-" }}
                                </h5>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Category:
                                </h4>
                            </td>

                            <td>
                                <div  v-for="(category, index) in model.category"
                                :key="index">
                                    <h4 style="color: teal">
                                        {{
                                            model.category[index].name
                                                ? model.category[index].name
                                                : "-"
                                        }}
                                    </h4>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h5
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Available Color:
                                </h5>
                            </td>
                            <td>
                                <h5
                                    class="font-weight-regular font-size-h3-sm"
                                    style="color: teal"
                                >
                                    {{ model.available_color != "" ? model.available_color : "-" }}
                                </h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Item Code:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{
                                        model.item_code != ""
                                            ? model.item_code
                                            : "-"
                                    }}
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Available Status:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{
                                        model.available_status_text != ""
                                            ? model.available_status_text
                                            : "-"
                                    }}
                                </h4>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Stock:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{ model.stock != "" ? model.stock : "-" }}
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Status:
                                </h5>
                            </td>
                            <td>
                                <h5
                                    class="font-weight-regular font-size-h3-sm"
                                    style="color: teal"
                                >
                                {{ model.status!= " " && model.status=="1"  ? "Active" : "Inactive" }}
                                </h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Description:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    v-if="model.description"
                                    style="color: teal"
                                >
                                    <span v-html="model.description"></span>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Feature Image:
                                </h4>
                            </td>
                            <td>
                                <a :href="model.featured_image">
                                    <v-img
                                        :src="model.featured_image"
                                        v-if="model.featured_image"
                                        width="100"
                                        height="100"
                                    >
                                    </v-img>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Set Unit:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{ model.set_unit != "" ? model.set_unit : "-" }}
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Unit Price:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{ model.unit_price != "" ? model.unit_price : "-" }}
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4
                                    class="font-weight-medium font-size-h3-sm"
                                    style="color: black"
                                >
                                    Price:
                                </h4>
                            </td>
                            <td>
                                <h4
                                    class="font-weight-regular font-size-h6-sm"
                                    style="color: teal"
                                >
                                    {{ model.price != "" ? model.price : "-" }}
                                </h4>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./product-view-modal.ts"></script>
