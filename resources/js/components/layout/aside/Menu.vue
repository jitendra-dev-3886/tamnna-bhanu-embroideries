<template>
    <ul class="menu-nav" style="background-color: black;">
        <router-link
            v-slot="{ href, navigate, isActive, isExactActive }"
            to="/dashboard"
        >
            <li
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                    isActive && 'menu-item-active',
                    isExactActive && 'menu-item-active',
                ]"
            >
                <a :href="href" class="menu-link" @click="navigate">
                    <i class="menu-icon flaticon-dashboard" />
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
        </router-link>
        <router-link
            v-slot="{ href, navigate, isActive, isExactActive }"
            to="/users"
        >
            <li
                v-index="$getConst('USERS')"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
          isActive && 'menu-item-active',
          isExactActive && 'menu-item-active'
        ]"
            >
                <a
                    :href="href"
                    class="menu-link"
                    @click="navigate"
                >
                    <i class="menu-icon flaticon-users"/>
                    <span class="menu-text">Users</span>
                </a>
            </li>
        </router-link>
        <router-link
              v-slot="{ href, navigate, isActive, isExactActive }"
              to="/activity-log"
            >
              <li
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item"
                :class="[
                  isActive && 'menu-item-active',
                  isExactActive && 'menu-item-active',
                ]"
              >
                <a :href="href" class="menu-link" @click="navigate">
                  <i class="menu-icon flaticon-users" />
                  <span class="menu-text">Activity Log</span>
                </a>
              </li>
            </router-link>

        <li class="menu-section">
            <h4 class="menu-text">Masters Library</h4>
            <i class="menu-icon flaticon-more-v2"></i>
        </li>

        <li
            aria-haspopup="true"
            data-menu-toggle="hover"
            class="menu-item menu-item-submenu"
            v-bind:class="{ 'menu-item-open': hasActiveChildren('/masters') }"
        >
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon flaticon2-digital-marketing"></i>
                <span class="menu-text">Masters</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="menu-submenu">
                <span class="menu-arrow"></span>
                <ul class="menu-subnav">

                    <router-link
                        to="/masters/homebanner"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('HOMEBANNERS')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                            isActive && 'menu-item-active',
                            isExactActive && 'menu-item-active'
                        ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Home Banners</span>
                            </a>
                        </li>
                    </router-link>

                    <router-link
                        to="/masters/customer"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('CUSTOMERS')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                            isActive && 'menu-item-active',
                            isExactActive && 'menu-item-active'
                        ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Customers</span>
                            </a>
                        </li>
                    </router-link>

                    <router-link
                        to="/masters/category"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('CATEGORIES')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active'
              ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Category</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        to="/masters/product"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('PRODUCTS')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active'
              ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Product</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link
                        to="/masters/order"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('ORDERS')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                isActive && 'menu-item-active',
                isExactActive && 'menu-item-active'
              ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Order</span>
                            </a>
                        </li>
                    </router-link>
                   <!-- <router-link
                        to="/masters/cart"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                    >
                        <li
                            v-index="$getConst('CARTS')"
                            aria-haspopup="true"
                            data-menu-toggle="hover"
                            class="menu-item"
                            :class="[
                            isActive && 'menu-item-active',
                            isExactActive && 'menu-item-active'
                        ]"
                        >
                            <a :href="href" class="menu-link" @click="navigate">
                                <i class="menu-bullet menu-bullet-dot">
                                    <span></span>
                                </i>
                                <span class="menu-text">Cart</span>
                            </a>
                        </li>
                    </router-link>     -->
                  </ul>
            </div>
        </li>
    </ul>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
@Component
class KTMenu extends Vue {
    hasActiveChildren(match: string): boolean {
        return this.$route.path.indexOf(match) !== -1;
    }
}
export default KTMenu;
</script>
