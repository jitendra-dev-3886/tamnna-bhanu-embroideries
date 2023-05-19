<template>
    <div>
        <v-tabs
            aria-label="Tabs"
            @change="refreshData()"
            class="mb-5"
            v-model="tab"
        >
            <v-tab
                href="#tab1"
                v-index="$getConst('PRODUCTS')"
                aria-label="product tab"
            >
                <p class="mt-2">Listing</p>
            </v-tab>
            <v-tab
                href="#tab2"
                v-importBulk="$getConst('PRODUCTS')"
                aria-label="Import tab"
            >
                <p class="mt-2">Import</p>
            </v-tab>

            <v-tab href="#tab3" aria-label="Import images tab">
                <!-- Zip upload tab -->
                <p class="mt-1">Import Zip</p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="product Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('PRODUCTS')"
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
                            <v-flex lg4 md4 sm12 xs12>
                                <v-text-field
                                    @input="onSearch"
                                    class="mx-4 mt-4"
                                    label="Search"
                                    prepend-inner-icon="search"
                                    v-model="searchModel"
                                    aria-label="Search product"
                                />
                            </v-flex>
                            <v-flex lg8 md8 sm12 xs12>
                                <div class="float-right mt-4">
                                    <v-menu
                                        v-model="filterMenu"
                                        :close-on-content-click="false"
                                        :nudge-width="200"
                                        offset-y
                                        aria-label="Product filter menu"
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
                                                    class="float-right filter-close-btn"
                                                    @click="filterMenu = false"
                                                    aria-label="Close filter"
                                                >
                                                    <v-icon small>
                                                        {{ icons.mdiClose }}
                                                    </v-icon>
                                                </v-btn>

                                                <v-select
                                                    v-model="category_id"
                                                    name="category"
                                                    item-text="name"
                                                    item-value="id"
                                                    multiple
                                                    :items="categoryList"
                                                    label="Category"
                                                    class="mt-4"
                                                    aria-label="Category"
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
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn
                                                @click="addProduct()"
                                                class="mb-2 mr-2"
                                                color="primary"
                                                dark
                                                v-on="on"
                                                v-store="$getConst('PRODUCTS')"
                                                aria-label="Add product"
                                            >
                                                <v-icon small>
                                                    {{ icons.mdiPlus }}
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Add</span>
                                    </v-tooltip>
                                    <export-btn
                                        :export-props="exportProps"
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        v-export="$getConst('PRODUCTS')"
                                    />
                                    <column-visibility-btn
                                        :headers="headers"
                                        :selected-headers="selectedHeaders"
                                        :toggle-select="toggleSelect"
                                        @close-menu="closeMenuColumn"
                                        @update-headers="setSelectedHeaders"
                                    ></column-visibility-btn>
                                    <template v-if="selected.length > 1">
                                        <multi-delete
                                            :delete-props="deleteProps"
                                            @click.native="multipleDelete()"
                                            @multiDelete="
                                                getData(), (selected = [])
                                            "
                                            ref="multipleDeleteBtn"
                                            v-deleteAll="$getConst('PRODUCTS')"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:[`item.description`]="{ item }">
                        <perfect-scrollbar
                            class="offcanvas-content scroll p-2"
                            style="max-height: 10vh; max-width: 1400px"
                        >
                            <span
                                v-if="item.description"
                                v-html="item.description"
                            ></span>
                            <span v-else>-</span>
                        </perfect-scrollbar>
                    </template>

                    <template v-slot:[`item.category.name`]="{ item }">
                        <div
                            v-for="(category, index) in item.category"
                            :key="index"
                        >
                            {{
                                item.category[index].name
                                    ? item.category[index].name
                                    : "-"
                            }}
                        </div>
                    </template>

                    <template v-slot:[`item.available_status`]="{ item }">
                        <span v-if="item.available_status_text">{{
                            item.available_status_text
                        }}</span>
                    </template>
                    <template v-slot:[`item.featured_image`]="{ item }">
                        <a :href="item.featured_image" target="_blank">
                            <v-img
                                :src="item.featured_image"
                                v-if="item.featured_image"
                                width="80"
                                height="80"
                            ></v-img>
                        </a>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        <!-- Light Gallery View Start-->
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="
                                        item.product_galleries &&
                                        item.product_galleries.length > 0
                                    "
                                    v-deleteGallery="$getConst('PRODUCTS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="openGallery(item)"
                                    aria-label="View Gallery icon"
                                >
                                    {{ icons.mdiImage }}
                                </v-icon>
                            </template>
                            <span>{{
                                $getConst("VIEW_PRODUCT_GALLERY_TOOLTIP")
                            }}</span>
                        </v-tooltip>
                        <!-- Light Gallery View End-->

                        <!-- Manage Gallery Start -->
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-deleteGallery="$getConst('PRODUCTS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="openGalleryModal(item.id)"
                                    aria-label="Manage Gallery icon"
                                >
                                    {{ icons.mdiImageEditOutline }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("MANAGE_IMAGES") }}</span>
                        </v-tooltip>
                        <!-- Manage Gallery End -->
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onView(item.id)"
                                    class="mr-2"
                                    small
                                    v-canAccess="$getConst('PRODUCTS')"
                                    v-on="on"
                                    aria-label="View product"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_DETAILS_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEdit(item.id)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-update="$getConst('PRODUCTS')"
                                    aria-label="Edit product"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="
                                        onStatusChange(item.id, item.status)
                                    "
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    :color="
                                        item.status == '1' ? 'red' : 'green'
                                    "
                                    v-update="$getConst('PRODUCTS')"
                                    aria-label="status change"
                                >
                                    {{
                                        item.status == "1"
                                            ? icons.mdiAccountRemove
                                            : icons.mdiAccountCheck
                                    }}
                                </v-icon>
                            </template>
                            <span>{{
                                item.status == "1" ? "Deactivate" : "Activate"
                            }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="deleteItem(item.id)"
                                    class="mr-2"
                                    small
                                    v-destroy="$getConst('PRODUCTS')"
                                    v-on="on"
                                    aria-label="Delete product"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("DELETE_TOOLTIP") }}</span>
                        </v-tooltip>
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
                            aria-label="Import product"
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
            @delete-success="getData(), (selected = [])"
            v-model="modalOpen"
            aria-label="Delete product confirmation modal"
        />
        <product-view-modal
            v-model="productViewModal"
            aria-label="product view modal"
        />

        <!-- Manage Gallery Start -->
        <multi-file-modal
            ref="managegallerymodal"
            :file-arr="galleryArr"
            store-name="product"
            variableMutation="Gallery"
            filePath="gallery"
            @delete-success="refresh()"
            aria-label="Manage Gallery modal"
        ></multi-file-modal>
        <!-- Manage Gallery End -->

        <image-viewer
            ref="imgViewer"
            key="imgViewer"
            aria-label="Image viewer"
        />
    </div>
</template>

<script lang="ts" src="./product-list.ts"></script>
