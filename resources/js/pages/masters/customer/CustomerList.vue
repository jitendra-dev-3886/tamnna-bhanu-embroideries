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
                v-index="$getConst('CUSTOMERS')"
                aria-label="category tab"
            >
                <p class="mt-2">Listing</p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="customer Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('CUSTOMERS')"
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
                                    aria-label="Search Customer"
                                />
                            </v-flex>
                            <v-flex lg8 md8 sm12 xs12>
                                <div class="float-right mt-4">
                                    <export-btn
                                        :export-props="exportProps"
                                        @click.native="setExport()"
                                        ref="exportbtn"
                                        v-export="$getConst('CUSTOMERS')"
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
                                            v-deleteAll="$getConst('CUSTOMERS')"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:[`item.user_status`]="{ item }">
                        <span v-if="item.user_status_text">{{
                            item.user_status_text
                        }}</span>
                    </template>

                    <template v-slot:[`item.actions`]="{ item }">
                        <template>
                            <v-btn
                                :color="
                                    item.user_status_text == 'Active'
                                        ? 'error'
                                        : 'success'
                                "
                                v-model="item.user_status"
                                @click="changeStatus(item.id, item.user_status)"
                                elevated
                                class="ma-2"
                                style="width: 100px"
                            >
                                <span
                                    class="text-truncate"
                                    style="width: 100px"
                                >
                                    {{
                                        item.user_status_text == "Inactive"
                                            ? "Activate"
                                            : "Deactivate"
                                    }}
                                </span>
                            </v-btn>
                        </template>
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
                            aria-label="Import costumer"
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
            aria-label="Delete customer confirmation modal"
        />
    </div>
</template>

<script lang="ts" src="./customer-list.ts"></script>
