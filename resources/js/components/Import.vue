
<template>
    <div>
        <v-card class="mx-auto mb-5">
            <v-card-text>
                <drag-and-drop-file
                    ref="dragAndDropFile"
                    :key="
                        importProps.modelName
                            ? importProps.modelName
                            : importProps.zipName
                    "
                    :import-props="importProps"
                    @refresh-table="getData()"
                    aria-label="Drag and drop file"
                />
            </v-card-text>
        </v-card>
        <v-data-table
            ref="table"
            v-model="selected"
            :headers="headers"
            :items="tableData"
            :loading="loading"
            :options.sync="options"
            :items-per-page="limit"
            :server-items-length="pageCount"
            :footer-props="footerProps"
            class="elevation-1"
            @update:options="onUpdateOptions"
        >
            <template v-slot:top>
                <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                        <h5 class="mt-4 ml-4">Import History</h5>
                    </v-flex>
                </v-layout>
                <v-layout>
                    <v-flex xs12 sm12 md4 lg4>
                        <v-text-field
                            v-model="searchModel"
                            label="Search"
                            class="mx-4 mt-4"
                            prepend-inner-icon="search"
                            @input="onSearch"
                            aria-label="Search import history"
                        />
                    </v-flex>
                </v-layout>
            </template>
            <template v-slot:[`item.created_at`]="{ item }">
                <span>{{ getDateTimeFormat(item.created_at) }}</span>
            </template>
            <template v-slot:[`item.user_id`]="{ item }">
                <span v-if="item.user">{{ item.user.name }}</span>
            </template>
            <template v-slot:[`item.status`]="{ item }">
                <span v-if="item.status_text">{{ item.status_text }}</span>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            v-if="item.status != '1'"
                            v-importBulk="$getConst('USERS')"
                            small
                            class="mr-2"
                            v-on="on"
                            @click="onView(item.id)"
                            aria-label="View errors"
                        >
                            {{ icons.mdiEye }}
                        </v-icon>
                    </template>
                    <span>{{ $getConst("VIEW_DETAILS_TOOLTIP") }}</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            v-importBulk="$getConst('USERS')"
                            small
                            class="mr-2"
                            v-on="on"
                            aria-label="Download"
                            @click="
                                downloadFile(item.file_path, 'DOWNLOAD_CSV')
                            "
                        >
                            {{ icons.mdiDownload }}
                        </v-icon>
                    </template>
                    <span>{{ $getConst("DOWNLOAD_TOOLTIP") }}</span>
                </v-tooltip>
            </template>
        </v-data-table>
        <import-error-modal
            v-model="importErrorDialog"
            :import-error-arr="importErrorArr"
            aria-label="Import error modal"
        />
    </div>
</template>

<script lang="ts" src="./import.ts"></script>
