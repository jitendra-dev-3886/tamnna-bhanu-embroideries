<template>
    <!-- begin::Scrolltop -->
    <div
        id="kt_scrolltop"
        ref="kt_scrolltop"
        class="scrolltop"
    >
    <span class="svg-icon">
      <inline-svg src="../media/svg/icons/Navigation/Up-2.svg"/>
    </span>
    </div>
    <!-- end::Scrolltop -->
</template>

<script lang="ts">
    import KTLayoutScrolltop from '../../../../assets/js/layout/extended/scrolltop.js';
    import {Component, Vue} from 'vue-property-decorator';

    @Component
    class KTScrollTop extends Vue {
        mounted() {
            // Init Scrolltop
            KTLayoutScrolltop.init(this.$refs.kt_scrolltop);
        }
    }

    export default KTScrollTop;
</script>
