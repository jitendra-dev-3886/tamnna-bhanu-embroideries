<template>
    <div>
        <v-tabs
            aria-label="Tabs"
            @change="refreshData()"
            class="mb-5"
            v-model="tab">
            <v-tab
                href="#tab1"
                v-index="$getConst('CARTS')"
                aria-label="cart tab"
            >
                <p class="mt-2">
                    Listing
                </p>
            </v-tab>
            <v-tab
                href="#tab2"
                v-importBulk="$getConst('CARTS')"
                aria-label="Import tab"
            >
                <p class="mt-2">
                    Import
                </p>
            </v-tab>
            
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="cart Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('CARTS')"
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
                                    aria-label="Search cart"
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
                                        aria-label="Cart filter menu"
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
                                                  v-model="user_id"
                                                  name="user"
                                                  item-text="name"
                                                  item-value="id"
                                                  :items="userList"
                                                  label="User"
                                                  class="mt-4"
                                                  aria-label="User"
                                                />
                                                <v-select
                                                  v-model="product_id"
                                                  name="product"
                                                  item-text="name"
                                                  item-value="id"
                                                  :items="productList"
                                                  label="Product"
                                                  class="mt-4"
                                                  aria-label="Product"
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
                                                @click="addCart()"
                                                class="mb-2 mr-2"
                                                color="primary"
                                                dark
                                                v-on="on"
                                                v-store="$getConst('CARTS')"
                                                aria-label="Add cart"
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
                                        v-export="$getConst('CARTS')"
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
                                            v-deleteAll="$getConst('CARTS')"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    
                    <template v-slot:[`item.user_id`]="{ item }">
                        <span v-if="item.user">{{ item.user.name }}</span>
                    </template>
            
                    <template v-slot:[`item.product_id`]="{ item }">
                        <span v-if="item.product">{{ item.product.name }}</span>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        
                        
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onView(item.id, false)"
                                    class="mr-2"
                                    small
                                    v-canAccess="$getConst('CARTS')"
                                    v-on="on"
                                    aria-label="View cart"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('VIEW_DETAILS_TOOLTIP') }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="onEdit(item.id, true)"
                                    class="mr-2"
                                    small
                                    v-on="on"
                                    v-update="$getConst('CARTS')"
                                    aria-label="Edit cart"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('EDIT_TOOLTIP') }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    @click="deleteItem(item.id)"
                                    class="mr-2"
                                    small
                                    v-destroy="$getConst('CARTS')"
                                    v-on="on"
                                    aria-label="Delete cart"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('DELETE_TOOLTIP') }}</span>
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
                            aria-label="Import cart"
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
            aria-label="Delete cart confirmation modal"
        />
        <cart-view-modal v-model="cartViewModal" aria-label="cart view modal" />
        
        
    </div>
</template>

<script lang="ts" src="./cart-list.ts"></script>
