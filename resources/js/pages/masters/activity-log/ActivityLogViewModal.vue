<template>
    <v-dialog
        :value="value"
        content-class="modal-lg modal-dialog"
        scrollable
        @click:outside="onCancel()"
        @keydown.esc="onCancel()"
    >
        <v-card>
            <v-card-title class="headline black-bg mb-4" primary-title>
                <span>View Details</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="onCancel">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <!-- Update View start -->
                <table
                    class="table table-striped mx-0 px-0"
                    v-if="modelObj.response_type == 1"
                >
                    <thead>
                    <tr>
                        <th class="font-weight-regular font-size-h6-sm">
                            Property
                        </th>
                        <th class="font-weight-regular font-size-h6-sm">
                            Latest
                        </th>
                        <th class="font-weight-regular font-size-h6-sm">
                            Old
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="modelObj">
                        <tr
                            v-for="(value, propertyName) in modelObj
                                    .properties.attributes"
                            :key="propertyName"
                            v-show="
                                    checkToShowPropertyOrNot(
                                        value,
                                        propertyName
                                    )
                                "
                        >
                            <td
                                class="font-weight-medium font-size-h6-sm"
                                style="width: 30%"
                            >
                                {{ propertyName | titleCase }}:
                            </td>
                            <template v-if="Array.isArray(value)">
                                <td
                                    class="
                                            font-weight-regular font-size-h6-sm
                                        "
                                >
                                    <template
                                        v-if="value[0] === Object(value[0])"
                                    >
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on }"
                                            >
                                                <v-icon
                                                    small
                                                    class="mr-2"
                                                    v-on="on"
                                                    @click="
                                                            openTableModal(
                                                                value,
                                                                propertyName
                                                            )
                                                        "
                                                >
                                                    {{ icons.mdiEye }}
                                                </v-icon>
                                            </template>
                                            <span>{{
                                                    $getConst(
                                                        "VIEW_DETAILS_TOOLTIP"
                                                    )
                                                }}</span>
                                        </v-tooltip>
                                        <table-modal
                                            :key="
                                                    `${propertyName}` +
                                                    'objTable'
                                                "
                                            v-model="tableModalView"
                                            :tableData="value"
                                        ></table-modal>
                                    </template>
                                    <template v-else>
                                            <span
                                                v-for="(item, index) in value"
                                                :key="index + item"
                                            >
                                                {{ item }}
                                                <template
                                                    v-if="
                                                        parseInt(index + 1) <
                                                        value.length
                                                    "
                                                >,</template
                                                >
                                            </span>
                                    </template>
                                </td>
                                <td
                                    v-if="
                                            modelObj.properties.old[
                                                propertyName
                                            ].length != value.length
                                        "
                                    class="
                                            font-weight-regular font-size-h6-sm
                                        "
                                >
                                    <template
                                        v-if="
                                                modelObj.properties.old[
                                                    propertyName
                                                ][0] ===
                                                Object(
                                                    modelObj.properties.old[
                                                        propertyName
                                                    ][0]
                                                )
                                            "
                                    >
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on }"
                                            >
                                                <v-icon
                                                    small
                                                    class="mr-2"
                                                    v-on="on"
                                                    @click="
                                                            openTableModal(
                                                                modelObj
                                                                    .properties
                                                                    .old[
                                                                    propertyName
                                                                ],
                                                                propertyName
                                                            )
                                                        "
                                                >
                                                    {{ icons.mdiEye }}
                                                </v-icon>
                                            </template>
                                            <span>{{
                                                    $getConst(
                                                        "VIEW_DETAILS_TOOLTIP"
                                                    )
                                                }}</span>
                                        </v-tooltip>
                                        <table-modal
                                            :key="
                                                    `${propertyName}` +
                                                    'objTable'
                                                "
                                            v-model="tableModalView"
                                            :tableData="value"
                                        ></table-modal>
                                    </template>
                                    <template v-else>
                                            <span
                                                v-for="(item, index) in modelObj
                                                    .properties.old[
                                                    propertyName
                                                ]"
                                                :key="index + item"
                                            >
                                                {{ item }}
                                                <template
                                                    v-if="
                                                        index + 1 <
                                                        modelObj.properties.old[
                                                            propertyName
                                                        ].length
                                                    "
                                                >,</template
                                                >
                                            </span>
                                    </template>
                                </td>
                            </template>
                            <template v-else-if="value === Object(value)">
                                <td
                                    class="
                                            font-weight-regular font-size-h6-sm
                                        "
                                >
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                v-on="on"
                                                @click="
                                                        openObjectModal(
                                                            value,
                                                            propertyName
                                                        )
                                                    "
                                            >
                                                {{ icons.mdiEye }}
                                            </v-icon>
                                        </template>
                                        <span>{{
                                                $getConst(
                                                    "VIEW_DETAILS_TOOLTIP"
                                                )
                                            }}</span>
                                    </v-tooltip>
                                    <object-modal
                                        :key="`${propertyName} ` + 'innObj'"
                                        v-model="objectModalView"
                                    ></object-modal>
                                </td>
                                <td
                                    v-if="
                                            value.id !==
                                            modelObj.properties.old[
                                                propertyName
                                            ].id
                                        "
                                    class="
                                            font-weight-regular font-size-h6-sm
                                        "
                                >
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                v-on="on"
                                                @click="
                                                        openObjectModal(
                                                            modelObj.properties
                                                                .old[
                                                                propertyName
                                                            ],
                                                            propertyName
                                                        )
                                                    "
                                            >
                                                {{ icons.mdiEye }}
                                            </v-icon>
                                        </template>
                                        <span>{{
                                                $getConst(
                                                    "VIEW_DETAILS_TOOLTIP"
                                                )
                                            }}</span>
                                    </v-tooltip>
                                    <object-modal
                                        :key="`${propertyName} ` + 'innObj'"
                                        v-model="objectModalView"
                                    ></object-modal>
                                </td>
                            </template>
                            <template v-else>
                                <td>
                                    {{ value }}
                                </td>
                                <td
                                    v-if="
                                            modelObj.properties.changes[
                                                propertyName
                                            ]
                                        "
                                >
                                    {{
                                    modelObj.properties.old[
                                    propertyName
                                    ]
                                    }}
                                </td>
                            </template>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <!-- Listing View start -->
                <template v-if="modelObj.response_type == 2">
                    <div>Total: {{ modelObj.properties.attributes.total }}</div>
                    <div>
                        Current Page:
                        {{ modelObj.properties.attributes.current_page }}
                    </div>
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th
                                    v-for="(value, propertyName) in modelObj
                                            .properties.attributes.data[0]"
                                    :key="propertyName"
                                    v-show="
                                            checkToShowPropertyOrNot(
                                                value,
                                                propertyName
                                            )
                                        "
                                    class="text-left"
                                >
                                    {{ propertyName | titleCase }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="(item, rowIndex) in modelObj
                                        .properties.attributes.data"
                                :key="item.id"
                            >
                                <template v-for="(val, prop) in item">
                                    <td
                                        v-if="
                                                Array.isArray(val) &&
                                                checkToShowPropertyOrNot(
                                                    val,
                                                    prop
                                                )
                                            "
                                        :key="'modelObj' + val.id"
                                    >
                                        <template
                                            v-if="val[0] === Object(val[0])"
                                        >
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                            on,
                                                        }"
                                                >
                                                    <v-icon
                                                        :key="`${prop}+btn`"
                                                        small
                                                        class="mr-2"
                                                        v-on="on"
                                                        @click="
                                                                openTableModal(
                                                                    val,
                                                                    prop
                                                                )
                                                            "
                                                    >
                                                        {{ icons.mdiEye }}
                                                    </v-icon>
                                                </template>
                                                <span>{{
                                                        $getConst(
                                                            "VIEW_DETAILS_TOOLTIP"
                                                        )
                                                    }}</span>
                                            </v-tooltip>
                                            <table-modal
                                                :key="
                                                        `${prop}${rowIndex}` +
                                                        'table'
                                                    "
                                                v-model="tableModalView"
                                            ></table-modal>
                                        </template>
                                        <template v-else>
                                                <span
                                                    v-for="(item, index) in val"
                                                    :key="item + index"
                                                >
                                                    {{ item }}
                                                    <template
                                                        v-if="
                                                            index + 1 <
                                                            val.length
                                                        "
                                                    >,</template
                                                    >
                                                </span>
                                        </template>
                                    </td>
                                    <td
                                        v-else-if="
                                                val === Object(val) &&
                                                checkToShowPropertyOrNot(
                                                    val,
                                                    prop
                                                )
                                            "
                                        :key="'modelObj' + val.id"
                                    >
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on }"
                                            >
                                                <v-icon
                                                    small
                                                    class="mr-2"
                                                    v-on="on"
                                                    @click="
                                                            openObjectModal(
                                                                val,
                                                                prop
                                                            )
                                                        "
                                                >
                                                    {{ icons.mdiEye }}
                                                </v-icon>
                                            </template>
                                            <span>{{
                                                    $getConst(
                                                        "VIEW_DETAILS_TOOLTIP"
                                                    )
                                                }}</span>
                                        </v-tooltip>
                                        <object-modal
                                            :key="
                                                    `${prop}${rowIndex}` + 'obj'
                                                "
                                            v-model="objectModalView"
                                        ></object-modal>
                                    </td>
                                    <td v-else :key="'modelObj' + val.id">
                                        {{ val }}
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </template>
                <!-- Created/Deleted View Start -->
                <table
                    class="table table-striped mx-0 px-0"
                    v-if="modelObj.response_type == 0"
                >
                    <tbody>
                    <template v-if="modelObj">
                        <tr
                            v-for="(value, propertyName) in modelObj
                                    .properties.attributes"
                            :key="propertyName"
                            v-show="
                                    checkToShowPropertyOrNot(
                                        value,
                                        propertyName
                                    )
                                "
                        >
                            <td
                                class="font-weight-medium font-size-h6-sm"
                                style="width: 30%"
                            >
                                {{ propertyName | titleCase }}:
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-if="Array.isArray(value)"
                            >
                                <template
                                    v-if="value[0] === Object(value[0])"
                                >
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                v-on="on"
                                                @click="
                                                        openTableModal(
                                                            value,
                                                            propertyName
                                                        )
                                                    "
                                            >
                                                {{ icons.mdiEye }}
                                            </v-icon>
                                        </template>
                                        <span>{{
                                                $getConst(
                                                    "VIEW_DETAILS_TOOLTIP"
                                                )
                                            }}</span>
                                    </v-tooltip>
                                    <table-modal
                                        :key="
                                                `${propertyName}` + 'objTable'
                                            "
                                        v-model="tableModalView"
                                        :tableData="value"
                                    ></table-modal>
                                </template>
                                <template v-else>
                                        <span
                                            v-for="(item, index) in value"
                                            :key="index + item"
                                        >
                                            {{ item }}
                                            <template
                                                v-if="index + 1 < value.length"
                                            >,</template
                                            >
                                        </span>
                                </template>
                            </td>
                            <td
                                class="font-weight-regular font-size-h6-sm"
                                v-else-if="value === Object(value)"
                            >
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            small
                                            class="mr-2"
                                            v-on="on"
                                            @click="
                                                    openObjectModal(
                                                        value,
                                                        propertyName
                                                    )
                                                "
                                        >
                                            {{ icons.mdiEye }}
                                        </v-icon>
                                    </template>
                                    <span>{{
                                            $getConst("VIEW_DETAILS_TOOLTIP")
                                        }}</span>
                                </v-tooltip>
                                <object-modal
                                    :key="`${propertyName} ` + 'innObj'"
                                    v-model="objectModalView"
                                ></object-modal>
                            </td>
                            <td v-else>
                                {{ value }}
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <!-- Import/Export Start -->
                <table
                    class="table table-striped mx-0 px-0"
                    v-if="modelObj.response_type == 3"
                >
                    <tbody>
                    <template>
                        <tr>
                            <td
                                class="font-weight-medium font-size-h6-sm"
                                style="width: 30%"
                            >
                                {{
                                modelObj.properties.attributes.filename
                                }}:
                            </td>
                            <td class="font-weight-regular font-size-h6-sm">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-importBulk="$getConst('USERS')"
                                            small
                                            class="mr-2"
                                            v-on="on"
                                            @click="
                                                    downloadFile(
                                                        modelObj.properties
                                                            .attributes
                                                            .file_path,
                                                        'DOWNLOAD_CSV'
                                                    )
                                                "
                                        >
                                            {{ icons.mdiDownload }}
                                        </v-icon>
                                    </template>
                                    <span>{{
                                            $getConst("DOWNLOAD_TOOLTIP")
                                        }}</span>
                                </v-tooltip>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script lang="ts" src="./activity-log-view-modal.ts"></script>
<style scoped>
    .theme--light.v-data-table > .v-data-table__wrapper > table > thead > tr > th {
        color: #000 !important;
    }
</style>
