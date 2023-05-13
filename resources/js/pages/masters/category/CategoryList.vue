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
                v-index="$getConst('CATEGORIES')"
                aria-label="category tab"
            >
                <p class="mt-2">Listing</p>
            </v-tab>
            <v-tab
                href="#tab2"
                v-importBulk="$getConst('CATEGORIES')"
                aria-label="Import tab"
            >
                <p class="mt-2">Import</p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="category Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('CATEGORIES')"
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
                                    aria-label="Search category"
                                />
                            </v-flex>
                            <v-flex lg8 md8 sm12 xs12>
                                <div class="float-right mt-4">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn
                                                @click="addCategory()"
                                                class="mb-2 mr-2"
                                                color="primary"
                                                dark
                                                v-on="on"
                                                v-store="
                                                    $getConst('CATEGORIES')
                                                "
                                                aria-label="Add category"
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
                                        v-export="$getConst('CATEGORIES')"
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
                                            v-deleteAll="
                                                $getConst('CATEGORIES')
                                            "
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
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onView(item.id, false)"
                                    class="mr-2"
                                    small
                                    v-canAccess="$getConst('CATEGORIES')"
                                    v-on="on"
                                    aria-label="View category"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_DETAILS_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEdit(item.id, true)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-update="$getConst('CATEGORIES')"
                                    aria-label="Edit category"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEditImage(item.id, true)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-update="$getConst('HOMEBANNERS')"
                                    aria-label="Edit homebanner"
                                >
                                    {{ icons.mdiImageEditOutline }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_IMAGE_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="deleteItem(item.id)"
                                    class="mr-2"
                                    small
                                    v-destroy="$getConst('CATEGORIES')"
                                    v-on="on"
                                    aria-label="Delete category"
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
                            aria-label="Import category"
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
            aria-label="Delete category confirmation modal"
        />
        <category-view-modal
            v-model="categoryViewModal"
            aria-label="category view modal"
        />
        <CategoryEditImages
            v-model="categoryEditImages"
            aria-label="categoryEditImages"
        />
    </div>
</template>

<script lang="ts" src="./category-list.ts"></script>
