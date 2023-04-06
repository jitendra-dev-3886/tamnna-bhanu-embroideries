<template>
    <div>
        <v-tabs
            aria-label="Tabs"
            v-model="tab"
            class="mb-5"
            @change="refreshData()"
        >
            <v-tab
                v-index="$getConst('USERS')"
                href="#tab1"
                aria-label="user tab"
            >
                <p class="mt-2">Listing</p>
            </v-tab>
            <v-tab
                v-importBulk="$getConst('USERS')"
                href="#tab2"
                aria-label="Import tab"
            >
                <p class="mt-2">Import</p>
            </v-tab>
            
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    aria-label="user Table"
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('USERS')"
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
                            <v-flex xs12 sm12 md4 lg4>
                                <v-text-field
                                    v-model="searchModel"
                                    label="Search"
                                    class="mx-4 mt-4"
                                    prepend-inner-icon="search"
                                    @input="onSearch"
                                    aria-label="Search user"
                                />
                            </v-flex>
                            <v-flex xs12 sm12 md8 lg8>
                                <div class="float-right mt-4">
                                    
                                    <v-menu
                                        v-model="filterMenu"
                                        :close-on-content-click="false"
                                        :nudge-width="200"
                                        offset-y
                                        aria-label="User filter menu"
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
                                                  v-model="role_id"
                                                  name="role"
                                                  item-text="name"
                                                  item-value="id"
                                                  :items="roleList"
                                                  label="Role"
                                                  class="mt-4"
                                                  aria-label="Role"
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
                                                v-store="$getConst('USERS')"
                                                color="primary"
                                                dark
                                                class="mb-2 mr-2"
                                                v-on="on"
                                                @click="addUser()"
                                                aria-label="Add user"
                                            >
                                                <v-icon small>
                                                    {{ icons.mdiPlus }}
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Add</span>
                                    </v-tooltip>
                                    <export-btn
                                        ref="exportbtn"
                                        v-export="$getConst('USERS')"
                                        :export-props="exportProps"
                                        @click.native="setExport()"></export-btn>
                                    <column-visibility-btn
                                        :toggle-select="toggleSelect"
                                        :headers="headers"
                                        :selected-headers="selectedHeaders"
                                        @update-headers="setSelectedHeaders"
                                        @close-menu="closeMenuColumn"
                                    ></column-visibility-btn>
                                    <template v-if="selected.length > 1">
                                        <multi-delete
                                            ref="multipleDeleteBtn"
                                            v-deleteAll="$getConst('USERS')"
                                            :delete-props="deleteProps"
                                            @click.native="multipleDelete()"
                                            @multiDelete="getData(), selected = []"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>
                    

              <template v-slot:[`item.role_id`]="{ item }">
                <span v-if="item.role">{{ item.role.name }}</span>
              </template>
    
                    <template v-slot:[`item.actions`]="{ item }">
                        
                        
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-canAccess="$getConst('USERS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="onView(item.id, false)"
                                    aria-label="View user"
                                >
                                    {{ icons.mdiEye }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("VIEW_DETAILS_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-update="$getConst('USERS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="onEdit(item.id, true)"
                                    aria-label="Edit user"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst("EDIT_TOOLTIP") }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="!isSuperAdmin(item)"
                                    v-destroy="$getConst('USERS')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="deleteItem(item.id)"
                                    aria-label="Delete user"
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
                            ref="importdata"
                            :import-props="importProps"
                            aria-label="Import user"
                        />
                    </v-card-text>
                </v-card>
            </v-tab-item>
            
        </v-tabs-items>
        <delete-modal
            v-model="modalOpen"
            :param-props="paramProps"
            :confirmation="confirmation"
            @delete-success="refresh()"
            aria-label="Delete user confirmation modal"
        />
        <user-view-modal v-model="userViewModal" aria-label="user view modal" />
        
        '
    </div>
</template>

<script src="./user-list.ts"></script>

<style scoped></style>
