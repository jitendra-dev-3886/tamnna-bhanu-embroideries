import common from "/resources/js/mixins/common.ts";

<template>
    <v-card>
        <v-card-title>Edit Banner Image</v-card-title>
        <v-flex>
            <div align="center">
            <v-img :src="model.featured_image" v-if="model.featured_image" width=50% height=50%>
            </v-img>
            <v-row
                align="right"
                justify="space-around"
            >
                <v-btn
                tile
                color="primary"
                >
                <v-icon center>
                    {{icons.mdiDelete}}
                </v-icon>

                </v-btn>
            </v-row>
        </div>
        </v-flex>
        <v-flex>
            <v-card-title>
                Upload Images
            </v-card-title>
        <template>
            <v-file-input
                show-size
                counter
                multiple
                label="File input"
            >
            </v-file-input>
            <div align="center">
                <v-btn
                    tile
                    color="primary"
                    align="center"
                    >
                    Submit
                </v-btn>
                <v-btn
                    tile
                    color="primary"
                    align="center"
                    @click=onCancel()
                    >
                    Cancel
                </v-btn>
            </div>
            </template>

    </v-flex>
    </v-card>
</template>

<script src="./homebanner-edit-images.ts"></script>
