<template>
    <v-dialog :value="value" content-class="modal-dialog">
        <v-card>
            <v-card-title class="headline black-bg" primary-title>
                Import Errors
                <v-spacer></v-spacer>
                <v-btn icon dark @click.native="$emit('input')">
                    <v-icon>{{ icons.mdiClose }}</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <template>
                    <v-simple-table>
                        <template v-slot:default>
                            <thead class="v-data-table-header">
                                <tr>
                                    <th>Row</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in importErrorArr"
                                    :key="item.row"
                                >
                                    <td>{{ item.row }}</td>
                                    <td>
                                        <template
                                            v-for="(err, index) in item.error"
                                        >
                                            {{ err }}
                                            <template
                                                v-if="index + 1 < err.length"
                                            >
                                                <br />
                                            </template>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </template>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from "vue-property-decorator";
import CommonServices from "../mixins/common";
import { IImportCSVLogResponse, IObject } from "../../assets/types/common";
@Component
class ImportErrorModal extends Mixins(CommonServices) {
    @Prop({ default: [] }) importErrorArr!: IImportCSVLogResponse[];
    @Prop({ default: false }) value!: boolean;
}

export default ImportErrorModal;
</script>
