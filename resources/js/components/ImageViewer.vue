<template>
    <div>
        <viewer
            v-if="images && images.length"
            ref="viewer"
            :images="images"
            :options="options"
            class="viewer"
            @inited="inited"
        >
            <img
                v-for="({ source, thumbnail }, index) in images"
                :key="index"
                :src="thumbnail"
                :data-source="source"
                class="image"
                alt="Gallery Images"
                aria-label="Gallery Images"
                title="Gallery Images"
            />
        </viewer>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import Viewer from "v-viewer";
import "viewerjs/dist/viewer.css";
import { Component } from "vue-property-decorator";
import { IObject } from "../../assets/types/common";

Vue.use(Viewer);

@Component
class ImageViewer extends Vue {
    options: { url: string } = {
        url: "data-source",
    };
    index: string | number = 0;
    images: Array<IObject> = [];
    viewer: typeof Viewer;

    inited(viewer: typeof Viewer): void {
        this.viewer = viewer;
        this.viewer["view"](this.index);
    }
    view(index: number): void {
        this.index = index;
        this.viewer["view"](this.index);
    }
    show(images: Array<IObject>, index = 0): void {
        if (this.images === images) {
            this.view(index);
            return;
        }
        this.images = images;
        this.index = index;
    }
}
export default ImageViewer;
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.image {
    display: none;
}
</style>
<style>
.viewer-loading > img {
    display: none; /* hide big images when it is loading */
}
</style>
