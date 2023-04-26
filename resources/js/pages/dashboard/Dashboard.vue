<template>
    <div>
        <v-layout row wrap class="m-0 card-stretch gutter-b">
            <v-flex xs12 lg12 class="mt-7"></v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-info px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="redirectTo(1)"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-info d-block my-2"
                    >
                        <v-icon class="text-info">{{
                            icons.mdiAccountMultiple
                        }}</v-icon>
                    </span>
                    <span
                        class="text-info font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                       Customers: {{ dashboardData.users.total_users }}
                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-danger px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="redirectTo(2)"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-danger d-block my-2"
                    >
                        <v-icon class="text-danger">{{
                            icons.mdiBasket
                        }}</v-icon>
                    </span>
                    <span
                        class="text-danger font-weight-bold font-size-h6"
                        v-if="dashboardData"
                    >
                    Categories: {{ dashboardData.categories.total_categories }}
                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-success px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="redirectTo(3)"
                   >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-success d-block my-2"
                    >
                        <v-icon class="text-success">{{
                            icons.mdiShopping
                        }}</v-icon>
                    </span>
                    <span
                        class="text-success font-weight-bold font-size-h1"
                        v-if="dashboardData"
                    >
                    Products: {{ dashboardData.products.total_products }}

                    </span>
                </div>
            </v-flex>
            <v-flex xs12 lg3 md3 sm6>
                <div
                    class="bg-light-warning px-6 py-8 rounded-xl ml-7 mr-7 mb-7 cursor-pointer"
                    @click="redirectTo(4)"
                >
                    <span
                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2"
                    >
                        <v-icon class="text-warning">{{
                            icons.mdiCart
                        }}</v-icon>
                    </span>
                    <span
                        class="text-warning font-weight-bold font-size-h1"
                        v-if="dashboardData"
                    >
                    Orders: {{ dashboardData.orders.total_orders }}
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
