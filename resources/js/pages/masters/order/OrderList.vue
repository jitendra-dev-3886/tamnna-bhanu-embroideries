<template>
    <div>
        <v-tabs
            aria-label="Tabs"
            @change="refreshData()"
            class="mb-5"
            v-model="tab">
            <v-tab
                href="#tab1"
                v-index="$getConst('ORDERS')"
                aria-label="order tab"
            >
                <p class="mt-2">
                    Listing
                </p>
            </v-tab>
           <!-- <v-tab
                href="#tab2"
                v-importBulk="$getConst('ORDERS')"
                aria-label="Import tab"
            >
                <p class="mt-2">
                    Import
                </p>
            </v-tab>

            <v-tab href="#tab3" aria-label="Import images tab">
                <p class="mt-1">Import Zip</p>
            </v-tab>-->
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="order Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('ORDERS')"
                    :headers="selectedHeaders"
                    :items="tableData"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="limit"
                    :server-items-length="pageCount"
                    :footer-props="footerProps"
                    class="elevation-1"
                    :show-select="true"
                    @update:options="onUpdateOptions"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex
                                lg4
                                md4
                                sm12
                                xs12
                            >
                                <v-text-field
                                    @input="onSearch"
                                    class="mx-4 mt-4"
                                    label="Search"
                                    prepend-inner-icon="search"
                                    v-model="searchModel"
                                    aria-label="Search order"
                                />
                            </v-flex>
                            <v-flex
                                lg8
                                md8
                                sm12
                                xs12
                            >
                                <div class="float-right mt-4">

                                    <v-menu
                                        v-model="filterMenu"
                                        :close-on-content-click="false"
                                        :nudge-width="200"
                                        offset-y
                                        aria-label="Order filter menu"
                                    >
                                        <template
                                            v-slot:activator="{ on: onMenu }"
                                        >
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                        on: onTooltip,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-btn
                                                        class="mb-2 mr-2"
                                                        color="indigo"
                                                        dark
                                                        v-bind="attrs"
                                                        v-on="{
                                                            ...onMenu,
                                                            ...onTooltip,
                                                        }"
                                                        aria-label="Filter"
                                                    >
                                                        <v-icon small>
                                                            {{
                                                                icons.mdiFilter
                                                            }}
                                                        </v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>{{
                                                    $getConst("FILTER_TOOLTIP")
                                                }}</span>
                                            </v-tooltip>
                                        </template>
                                        <v-card class="p-4">
                                            <v-list>
                                                <v-btn
                                                    text
                                                    class="
                                                        float-right
                                                        filter-close-btn
                                                    "
                                                    @click="filterMenu = false"
                                                    aria-label="Close filter"
                                                >
                                                    <v-icon small>
                                                        {{ icons.mdiClose }}
                                                    </v-icon>
                                                </v-btn>

                                                <v-select
                                                  v-model="viewModel.user_name"
                                                  name="user"
                                                  item-text="name"
                                                  item-value="id"
                                                  :items=customerList
                                                  label="Customer"
                                                  class="mt-4"
                                                  aria-label="User"
                                                />
                                            </v-list>
                                            <v-card-actions>
                                                <v-spacer />
                                                <v-btn
                                                    color="primary"
                                                    text
                                                    @click="changeFilter()"
                                                    aria-label="Apply Filter"
                                                >
                                                    Apply Filter
                                                </v-btn>
                                                <v-btn
                                                    text
                                                    @click="resetFilter()"
                                                    aria-label="Reset Filter"
                                                >
                                                    Reset Filter
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-menu>
                                    <export-btn
                                        :export-props="exportProps"
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        v-export="$getConst('ORDERS')"
                                    />
                                    <column-visibility-btn
                                        :headers="headers"
                                        :selected-headers="selectedHeaders"
                                        :toggle-select="toggleSelect"
                                        @close-menu="closeMenuColumn"
                                        @update-headers="setSelectedHeaders"
                                    ></column-visibility-btn>
                                    <template v-if="selected.length>1">
                                        <multi-delete
                                            :delete-props="deleteProps"
                                            @click.native="multipleDelete()"
                                            @multiDelete="getData(), selected = []"
                                            ref="multipleDeleteBtn"
                                            v-deleteAll="$getConst('ORDERS')"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:[`item.user_id`]="{ item }">
                        <span v-if="item.user">{{ item.user.name }}</span>
                    </template>

                    <!--Commenting as Status not be displayed-->
                    <!--<template v-slot:[`item.order_status`]="{ item }">
                        <span v-if="item.order_status_text">{{ item.order_status_text }}</span>
                    </template>-->

                    <!--Display product name fetching from order Products array from API-->
                        <template v-slot:[`item.order_products.product.name`]="{ item }">
                            <span v-if="item.order_products">
                                <template v-for="(order_product, index) in item.order_products">
                                <div>{{ order_product.product.name }}</div>
                                </template>
                            </span>
                        </template>



                    <template v-slot:[`item.gst`]="{ item }">
                        <span>{{parseFloat(item.gst).toFixed(2)}}</span>
                    </template>
                    <template v-slot:[`item.payment_amount`]="{ item }">
                        <span>{{parseFloat(item.payment_amount).toFixed(2)}}</span>
                    </template>
                    <template v-slot:[`item.created_at`]="{item}">
                        <span>{{ computedDateFormattedMomentjs(item.created_at) }}</span>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">

                        <!-- Light Order View Start-->
                        <!--<v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="
                                        item.order_products &&
                                        item.order_products.length > 0
                                    "
                                    v-deleteProductId="$getConst('ORDERS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="openProductId(item)"
                                    aria-label="View Product Id icon"
                                >
                                    {{ icons.mdiImage }}
                                </v-icon>
                            </template>
                            <span>{{
                                $getConst("VIEW_PRODUCT_GALLERY_TOOLTIP")
                            }}</span>
                        </v-tooltip>-->
                        <!-- Light Order  View End-->


                        <!-- Manage Order  Start -->
                       <!-- <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="
                                        item.order_products &&
                                        item.order_products.length > 0
                                    "
                                    v-deleteProductId="$getConst('ORDERS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="openProductIdModal(item.id)"
                                    aria-label="Manage Product Id icon"
                                >
                                    {{ icons.mdiImageEditOutline }}
                                </v-icon>
                            </template>
                            <span>{{
                                $getConst("MANAGE_IMAGES")
                            }}</span>
                        </v-tooltip>-->
                        <!-- Manage Order  End -->
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onView(item.id)"
                                    class="mr-2"
                                    small
                                    v-canAccess="$getConst('ORDERS')"
                                    v-on="on"
                                    aria-label="View order"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('VIEW_DETAILS_TOOLTIP') }}</span>
                        </v-tooltip>
                        <!--Commenting Edit and Delete Order as not needed-->
                       <!-- <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEdit(item.id, true)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-update="$getConst('ORDERS')"
                                    aria-label="Edit order"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('EDIT_TOOLTIP') }}</span>
                        </v-tooltip>-->
                       <!-- <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="deleteItem(item.id)"
                                    class="mr-2"
                                    small
                                    v-destroy="$getConst('ORDERS')"
                                    v-on="on"
                                    aria-label="Delete order"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('DELETE_TOOLTIP') }}</span>
                        </v-tooltip>-->
                    </template>
                </v-data-table>
            </v-tab-item>
            <v-tab-item value="tab2">
                <v-card flat>
                    <v-card-text>
                        <import
                            :import-props="importProps"
                            key="importCsv"
                            ref="importdata"
                            aria-label="Import order"
                        />
                    </v-card-text>
                </v-card>
            </v-tab-item>

            <v-tab-item value="tab3">
                <v-card flat>
                    <v-card-text>
                        <import
                            ref="importdata"
                            :import-props="importZipProps"
                            aria-label="Import Zip"
                        />
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
        <delete-modal
            :confirmation="confirmation"
            :param-props="paramProps"
            @delete-success="getData(), selected=[]"
            v-model="modalOpen"
            aria-label="Delete order confirmation modal"
        />
        <order-view-modal v-model="orderViewModal" aria-label="order view modal" />


        <!-- Manage Product Id Start -->
       <!-- <multi-file-modal
            ref="manageproductidmodal"
            :file-arr="productIdArr"
            store-name="order"
            variableMutation="ProductId"
            filePath="product_id"
            @delete-success="refresh()"
            aria-label="Manage Product Id modal"
        ></multi-file-modal>-->
        <!-- Manage Product Id End -->

        <image-viewer
            ref="imgViewer"
            key="imgViewer"
            aria-label="Image viewer"
        />
    </div>
</template>

<script lang="ts" src="./order-list.ts"></script>
