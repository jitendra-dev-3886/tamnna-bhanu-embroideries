<template>
    <div>
        <v-container fluid class="">
            <v-layout>
                <v-flex
                    xs12
                    sm12
                    class="headline black-bg main-page-title"
                    primary-title
                >
                    <!--                    <template v-if="getPageTitle">-->
                    <!--                        <p-->
                    <!--                            class="m-0"-->
                    <!--                            v-html="getPageTitle"-->
                    <!--                        />-->
                    <!--                    </template>-->
                </v-flex>
            </v-layout>
            <div class="m-b-20">
                <v-flex class="grey-bg p-t-20 p-b-20">
                    <form
                        id="permission_form"
                        method="POST"
                        name="permission_form"
                        role="form"
                        enctype="multipart/form-data"
                    >
                        <v-layout row wrap class="pl-3 pr-3">
                            <v-flex lg12 md12 sm12 xs12>
                                <v-select
                                    v-model="role_id"
                                    v-getPermissionsByRole="
                                        $getConst('ROLEPERMISSIONS')
                                    "
                                    class="mb-5 mt-1"
                                    :items="roleList"
                                    item-value="id"
                                    item-text="name"
                                    name="role_id"
                                    label="Role"
                                    persistent-hint
                                    @change="getPermissions"
                                />
                            </v-flex>
                        </v-layout>
                        <v-container
                            v-if="permissions.length > 0"
                            v-setUnsetPermissionToRole="
                                $getConst('ROLEPERMISSIONS')
                            "
                        >
                            <!-- Stack the columns on mobile by making one full-width and the other half-width -->
                            <v-row
                                v-for="permission in permissions"
                                :key="permission.id"
                            >
                                <v-col cols="12" md="12" class="p-0">
                                    <v-divider class="m-0" />
                                </v-col>
                                <v-col cols="12" md="2">
                                    <h6 class="pa-2">
                                        {{ permission.label }}
                                    </h6>
                                </v-col>
                                <v-col cols="12" md="10" sm="12">
                                    <v-row>
                                        <v-col
                                            v-for="subPermission in permission.sub_permissions"
                                            :key="subPermission.id"
                                            cols="12"
                                            md="2"
                                            sm="4"
                                            xs="12"
                                            class="p-lg-0"
                                        >
                                            <v-checkbox
                                                v-model="
                                                    subPermission.is_permission
                                                "
                                                v-setUnsetPermissionToRole="
                                                    $getConst('ROLEPERMISSIONS')
                                                "
                                                class="mt-0"
                                                type="checkbox"
                                                true-value="1"
                                                false-value="0"
                                                :label="subPermission.label"
                                                @change="
                                                    editPermission(
                                                        subPermission
                                                    )
                                                "
                                            />
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-container>
                    </form>
                </v-flex>
            </div>
        </v-container>
    </div>
</template>

<script lang="ts" src="./permission.ts"></script>
