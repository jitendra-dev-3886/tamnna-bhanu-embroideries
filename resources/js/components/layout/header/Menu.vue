<template>
    <!--<ul class="menu-nav">
      <router-link
        to="users"
        v-slot="{ href, navigate, isActive, isExactActive }"
      >
        <li
          aria-haspopup="true"
          data-menu-toggle="hover"
          class="menu-item"
          :class="[
            isActive && 'menu-item-active',
            isExactActive && 'menu-item-active'
          ]"
        >
          <a :href="href" class="menu-link" @click="navigate">
            <span class="menu-text"> User </span>
          </a>
        </li>
      </router-link>

      <router-link
        to="role"
        v-slot="{ href, navigate, isActive, isExactActive }"
      >
        <li
          aria-haspopup="true"
          data-menu-toggle="hover"
          class="menu-item"
          :class="[
            isActive && 'menu-item-active',
            isExactActive && 'menu-item-active'
          ]"
        >
          <a :href="href" class="menu-link" @click="navigate">
            <span class="menu-text"> Role </span>
          </a>
        </li>
      </router-link>

    </ul>-->
</template>

<script>
    import Component from "vue-class-component";
    import Vue from "vue";
    @Component
    class KTMenu extends Vue {
        hasActiveChildren(match) {
            return this.$route.path.indexOf(match) !== -1;
        }
    }
    export default KTMenu;
</script>
