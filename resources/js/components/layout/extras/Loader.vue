<template>
    <!-- begin::Page loader -->
    <div class="page-loader page-loader-logo">
        <div class="progress progress--indeterminate">
            <div class="bar" />
        </div>
    </div>
    <!-- end::Page Loader -->
</template>

<script lang="ts">
    import {Component, Vue, Prop} from 'vue-property-decorator';
    @Component
    class Loader extends Vue {
        @Prop({default: ''}) logo!: string;
        @Prop({default: ''}) spinnerClass!: string;

    }
    export default Loader;

</script>
