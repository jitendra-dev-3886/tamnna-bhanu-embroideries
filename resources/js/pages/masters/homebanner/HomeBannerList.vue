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
                v-index="$getConst('HOMEBANNERS')"
                aria-label="homebanner tab"
            >
                <p class="mt-2">Listing</p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="homebanner Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('HOMEBANNERS')"
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
                                                @click="addHomebanner()"
                                                class="mb-2 mr-2"
                                                color="primary"
                                                dark
                                                v-on="on"
                                                v-store="
                                                    $getConst('HOMEBANNERS')
                                                "
                                                aria-label="Add homebanner"
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
                                        v-export="$getConst('HOMEBANNERS')"
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
                                                $getConst('HOMEBANNERS')
                                            "
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:[`item.banner_status`]="{ item }">
                        <span v-if="item.banner_status === '1'">Active</span>
                        <span v-else-if="item.banner_status === '0'"
                            >Inactive</span
                        >
                    </template>

                    <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onView(item.id)"
                                    class="mr-2"
                                    small
                                    v-canAccess="$getConst('HOMEBANNERS')"
                                    v-on="on"
                                    aria-label="View homebanner"
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
                                    v-update="$getConst('HOMEBANNERS')"
                                    aria-label="Edit homebanner"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEditImage(item.id)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-updateBannerImage="$getConst('HOMEBANNERS')"
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
                                    v-destroy="$getConst('HOMEBANNERS')"
                                    v-on="on"
                                    aria-label="Delete homebanner"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("DELETE_TOOLTIP") }}</span>
                        </v-tooltip>
                    </template>
                    <template v-slot:[`item.featured_image`]="{ item }">
                        <a :href="item.featured_image" target="_blank">
                            <v-img
                                :src="item.featured_image"
                                v-if="item.featured_image"
                                width="70"
                                height="70"
                            ></v-img>
                        </a>
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
                            aria-label="Import homebanner"
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
            aria-label="Delete homebanner confirmation modal"
        />
        <HomeBannerViewModal
            v-model="homeBannerViewModal"
            aria-label="homebanner view modal"
        />
        <HomeBannerEditImages
            v-if="homeBannerEditImages"
            v-model="homeBannerEditImages"
            aria-lable="homebanner edit images"
        />
    </div>
</template>

<script lang="ts" src="./homebanner-list.ts"></script>
