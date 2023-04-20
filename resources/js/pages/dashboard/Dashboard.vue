<template>
    <div v-getTicketStatusCount="$getConst('TICKETS')">
        <v-layout row wrap class="m-0 card-stretch gutter-b">
            <v-flex xs12 lg12 class="mt-7"></v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-info px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="viewTicktLists('-1')"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-info d-block my-2"
                    >
                        <v-icon class="text-info">{{
                            icons.mdiPaperclip
                        }}</v-icon>
                    </span>
                    <span
                        class="text-info font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                       Orders:
                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-warning px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="
                        viewTicktLists(
                            $getConst('TICKET_STATUS_VALUES').assigned
                        )
                    "
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2"
                    >
                        <v-icon class="text-warning">{{
                            icons.mdiPencil
                        }}</v-icon>
                    </span>
                    <span
                        class="text-warning font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                        {{ $getConst("TICKET_STATUS_TYPE_TEXT")["0"] }}:
                        {{ dashboardData.customers }}
                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-danger px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="
                        viewTicktLists(
                            $getConst('TICKET_STATUS_VALUES').inProgress
                        )
                    "
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-danger d-block my-2"
                    >
                        <v-icon class="text-danger">{{
                            icons.mdiExport
                        }}</v-icon>
                    </span>
                    <span
                        class="text-danger font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                        {{ $getConst("TICKET_STATUS_TYPE_TEXT")["1"] }}:
                        {{ dashboardData.categories }}
                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-success px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="
                        viewTicktLists(
                            $getConst('TICKET_STATUS_VALUES').completed
                        )
                    "
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-success d-block my-2"
                    >
                        <v-icon class="text-success">{{
                            icons.mdiDownload
                        }}</v-icon>
                    </span>
                    <span
                        class="text-success font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                        {{ $getConst("TICKET_STATUS_TYPE_TEXT")["2"] }}:
                        {{ dashboardData.completed }}
                    </span>
                </div>
            </v-flex>
        </v-layout>
    </div>
</template>
<script lang="ts" src="./dashboard.ts"></script>
<style scoped>
.card-body {
    margin: 0 0 10px 0;
}
.card-hd {
    margin-bottom: 0;
    background-color: #ffffff;
}
.card-right {
    border-right: #007190 1px solid;
}
.card-pad-left {
    padding-left: 30px;
}
</style>
