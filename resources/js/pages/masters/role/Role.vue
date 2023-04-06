<template>
    <div>
        <v-layout>
            <v-flex
                xs12
                sm6
                class="headline black-bg main-page-title"
                primary-title
            >
<!--                <template v-if="getPageTitle">-->
<!--                    <p-->
<!--                        class="m-0"-->
<!--                        v-html="getPageTitle"-->
<!--                    />-->
<!--                </template>-->
            </v-flex>
            <v-flex
                xs12
                sm6 class="float-right">
                <v-btn @click="addrole()"
                       class="mb-2 mr-2 btn theme-btn float-right"
                >
                    <v-icon small class="mr-1">{{ icons.mdiPlus }}</v-icon>
                    Add Role
                </v-btn>
            </v-flex>
        </v-layout>
        <v-tabs
            v-model="tab"
            class="mb-5"
        >
            <v-tab href="#tab1">
                <p class="mt-2">
                    <img src="images/listing.png" class="mr-2">
                    Listing
                </p>
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item value="tab1">
                <v-data-table
                    ref="table"
                    v-model="selected"
                    v-index="$getConst('ROLES')"
                    :headers="headers"
                    :items="tableData"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="limit"
                    :footer-props="footerProps"
                    :server-items-length="pageCount"
                    class="elevation-1"
                    :show-select="true"
                    @update:options="onUpdateOptions"
                >
                    <template v-slot:top>
                        <v-layout>
                            <v-flex
                                xs12
                                sm12
                                md4
                                lg4
                            >
                                <v-text-field
                                    v-model="searchModel"
                                    label="Search"
                                    class="mx-4"
                                    prepend-inner-icon="search"
                                    @input="onSearch"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                                sm12
                                md8
                                lg8
                            >
                                <div class="float-right mt-4">
                                    <!--<v-icon class="mr-3">{{ icons.mdiDownload }} </v-icon>
                                    <v-icon class="">{{ icons.mdiDelete }} </v-icon>-->
                                    <export-btn
                                        ref="exportbtn"
                                        v-export="$getConst('ROLES')"
                                        :export-props="exportProps"
                                        @click.native="setExport()"
                                    />
                                    <template v-if="selected.length>1">
                                        <multi-delete
                                            ref="multipleDeleteBtn"
                                            v-deleteAll="$getConst('ROLES')"
                                            :delete-props="deleteProps"
                                            @click.native="multipleDelete()"
                                            @multiDelete="getData(), selected = []"
                                        />
                                    </template>
                                </div>
                            </v-flex>
                        </v-layout>
                    </template>

                    <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="item && item.id != '1'"
                                    v-update="$getConst('ROLES')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="editItem(item.id)"
                                >
                                    {{ icons.mdiPencil }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('EDIT_TOOLTIP') }}</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    v-if="item && item.id != '1'"
                                    v-destroy="$getConst('ROLES')"
                                    small
                                    class="mr-2"
                                    v-on="on"
                                    @click="deleteItem(item.id)"
                                >
                                    {{ icons.mdiDelete }}
                                </v-icon>
                            </template>
                            <span>{{ $getConst('DELETE_TOOLTIP') }}</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-tab-item>
        </v-tabs-items>
        <add-role v-model="addRoleModal" @refresh-table="getData()"/>
        <delete-modal @delete-success="deleteSuccess"
            v-model="modalOpen"
            :param-props="paramProps"
            :confirmation="confirmation"
        />
    </div>
</template>

<script lang="ts" src="./role.ts"></script>
