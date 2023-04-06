<template>
  <div>
    <v-tabs v-model="tab" class="mb-5" @change="refreshData()">
      <v-tab href="#tab1">
        <p class="mt-2">Listing</p>
      </v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item value="tab1">
        <v-data-table
          ref="table"
          v-model="selected"
          v-index="$getConst('USERS')"
          :headers="headers"
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
                />
              </v-flex>
              <v-flex xs12 sm12 md8 lg8>
                <div class="float-right mt-4">
                  <v-menu
                    v-model="filterMenu"
                    :close-on-content-click="false"
                    :nudge-width="200"
                    offset-y
                  >
                    <template v-slot:activator="{ on: onMenu }">
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on: onTooltip, attrs }">
                          <v-btn
                            class="mb-2 mr-2"
                            color="indigo"
                            dark
                            v-bind="attrs"
                            v-on="{ ...onMenu, ...onTooltip }"
                          >
                            <v-icon small>
                              {{ icons.mdiFilter }}
                            </v-icon>
                          </v-btn>
                        </template>
                        <span>{{ $getConst("FILTER_TOOLTIP") }}</span>
                      </v-tooltip>
                    </template>
                    <v-card class="p-4">
                      <v-list>
                        <v-btn
                          text
                          class="float-right filter-close-btn"
                          @click="filterMenu = false"
                        >
                          <v-icon small>
                            {{ icons.mdiClose }}
                          </v-icon>
                        </v-btn>

                        <v-select
                          v-model="model.module"
                          name="module"
                          multiple
                          item-text="name"
                          item-value="id"
                          :items="getModuleList"
                          label="Module"
                          class="mt-4"
                        />
                        <v-select
                          v-model="model.user_id"
                          multiple
                          name="user"
                          item-text=""
                          item-value="id"
                          :items="getUserList"
                          label="User"
                          class="mt-4"
                        />
                        <period-filter v-model="model"></period-filter>
                      </v-list>
                      <v-card-actions>
                        <v-spacer />
                        <v-btn color="primary" text @click="changeFilter()">
                          Apply Filter
                        </v-btn>
                        <v-btn text @click="resetFilter()">
                          Reset Filter
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-menu>

                </div>
              </v-flex>
            </v-layout>
          </template>
          <template v-slot:[`item.created_at`]="{ item }">
              <span>{{ getDateTimeFormat(item.created_at) }}</span>
          </template>
          <template v-slot:[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon
                  small
                  class="mr-2"
                  v-on="on"
                  @click="onView(item)"
                >
                  {{ icons.mdiEye }}
                </v-icon>
              </template>
              <span>{{ $getConst('VIEW_DETAILS_TOOLTIP') }}</span>
            </v-tooltip>
          </template>
        </v-data-table>
      </v-tab-item>
    </v-tabs-items>
    <activity-log-view-modal v-model="activityLogViewModal" :modelObj="modelObj"></activity-log-view-modal>
  </div>
</template>
<script lang="ts" src="./activity-log.ts"></script>
