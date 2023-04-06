<template>
    <!-- begin:: Header Topbar -->
    <div class="topbar">
        <!--begin: User Bar -->
        <KTQuickUser />
        <!--end: User Bar -->
    </div>
    <!-- end:: Header Topbar -->
</template>

<style lang="scss">
    .topbar {
        .dropdown-toggle {
            padding: 0;
            &:hover {
                text-decoration: none;
            }

            &.dropdown-toggle-no-caret {
                &:after {
                    content: none;
                }
            }
        }

        .dropdown-menu {
            margin: 0;
            padding: 0;
            outline: none;
            .b-dropdown-text {
                padding: 0;
            }
        }
    }
</style>

<script lang="ts">
    import KTQuickUser from '../extras/offcanvas/QuickUser.vue';
    import {Component, Vue} from 'vue-property-decorator'
    @Component({
        components: {
            KTQuickUser
        },
    })
    class KTTopbar extends Vue {}
    export default KTTopbar;
</script>
