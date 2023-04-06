<template>
    <!-- begin:: Footer -->
    <div
        id="kt_footer"
        class="footer bg-white py-4 d-flex flex-lg-column"
    >
        <div
            class="d-flex align-items-center justify-content-between"
            :class="{ 'container-fluid': widthFluid, container: !widthFluid }"
        >
            <div class="text-dark">
        <span class="text-muted font-weight-bold mr-2">
          {{ currentYear }} &nbsp;&copy;&nbsp;
        </span>
                <a
                    href="https://easternts.com.au/"
                    target="_blank"
                    class="text-dark-75 text-hover-primary"
                >
                    Eastern Techno Solutions
                </a>
            </div>
            <div class="nav nav-dark">
                <a
                    href="https://easternts.com.au/"
                    target="_blank"
                    class="nav-link pr-3 pl-0"
                >
                    About
                </a>
                <a
                    href="https://www.easternts.com.au/contact-us/"
                    target="_blank"
                    class="nav-link pl-3 pr-0"
                >
                    Contact
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Footer -->
</template>

<script lang="ts">
    import {ConfigModule} from "@/store/config";
    import {Component, Vue} from 'vue-property-decorator'

    @Component
    class KTFooter extends Vue {
        public currentYear: number|string = '';

        mounted() {
            const d = new Date();
            this.currentYear = d.getFullYear();
        }

        /**
         * Check if footer container is fluid
         */
        get widthFluid() {
            return ConfigModule.layoutConfig('footer.width') === 'fluid';
        }
    }

    export default KTFooter;
</script>
