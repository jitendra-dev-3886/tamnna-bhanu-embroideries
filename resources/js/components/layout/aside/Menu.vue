<template>
    <ul class="menu-nav" style="background-color: black">
        <!--search for menu-->
        <v-text-field
            v-model="searchMenu"
            label="Search Menu"
            class="px-6 py-0 my-0 custom-label-color"
            color="#a2a3b7"
            @input="onSearchMenu"
        >
            <template v-slot:prepend-inner>
                <v-icon color="#a2a3b7" class="mr-2">search</v-icon>
            </template>
        </v-text-field>

        <!-- Menu Array Start From Here -->
        <template v-for="(menu, index) in menus">
            <li
                class="menu-section"
                v-if="menu.menuType == 'divider'"
                :key="`menu${index}`"
            >
                <h4 class="menu-text">{{ menu.label }}</h4>
                <i class="menu-icon flaticon-more-v2"></i>
            </li>
            <router-link
                v-slot="{ href, navigate, isActive, isExactActive }"
                :to="menu.url"
                :key="`menu${index}`"
                v-else-if="menu.menuType == 'single'"
            >
                <li
                    v-index:[menu.directiveName]="
                        $getConst(menu.permissionName)
                    "
                    aria-haspopup="true"
                    data-menu-toggle="hover"
                    class="menu-item"
                    :class="[
                        isActive && 'menu-item-active',
                        isExactActive && 'menu-item-active',
                    ]"
                >
                    <a :href="href" class="menu-link" @click="navigate">
                        <i :class="`menu-icon ${menu.icon}`" />
                        <span class="menu-text">{{ menu.label }}</span>
                    </a>
                </li>
            </router-link>
            <li
                v-else-if="menu.menuType == 'nested'"
                :key="`menu${index}`"
                aria-haspopup="true"
                data-menu-toggle="hover"
                class="menu-item menu-item-submenu"
                v-bind:class="{
                    'menu-item-open': hasActiveChildren(menu.matchUrl),
                }"
            >
                <a href="#" class="menu-link menu-toggle">
                    <i :class="`menu-icon ${menu.icon}`" />
                    <span class="menu-text">{{ menu.label }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                    <span class="menu-arrow"></span>
                    <ul class="menu-subnav">
                        <template
                            v-for="(subMenu, subMenuIndex) in menu.subMenus"
                        >
                            <router-link
                                v-if="subMenu.menuType == 'single'"
                                :key="`subMenu${index}_${subMenuIndex}`"
                                :to="subMenu.url"
                                v-slot="{
                                    href,
                                    navigate,
                                    isActive,
                                    isExactActive,
                                }"
                            >
                                <li
                                    v-index:[subMenu.directiveName]="
                                        $getConst(subMenu.permissionName)
                                    "
                                    aria-haspopup="true"
                                    data-menu-toggle="hover"
                                    class="menu-item"
                                    :class="[
                                        isActive && 'menu-item-active',
                                        isExactActive && 'menu-item-active',
                                    ]"
                                >
                                    <a
                                        :href="href"
                                        class="menu-link"
                                        @click="navigate"
                                        :data-test-id="subMenu.label"
                                    >
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">{{
                                            subMenu.label
                                        }}</span>
                                    </a>
                                </li>
                            </router-link>
                            <li
                                v-else-if="subMenu.menuType == 'nested'"
                                :key="`subMenu${index}_${subMenuIndex}`"
                                aria-haspopup="true"
                                data-menu-toggle="hover"
                                class="menu-item menu-item-submenu"
                                v-index:[subMenu.directiveName]="
                                    $getConst(subMenu.permissionName)
                                "
                                v-bind:class="{
                                    'menu-item-open': hasActiveChildren(
                                        subMenu.matchUrl
                                    ),
                                }"
                            >
                                <a href="#" class="menu-link menu-toggle">
                                    <i :class="`menu-icon ${subMenu.icon}`" />
                                    <span class="menu-text">{{
                                        subMenu.label
                                    }}</span>
                                    <i class="menu-arrow"></i>
                                </a>

                                <div class="menu-submenu">
                                    <span class="menu-arrow"></span>
                                    <ul class="menu-subnav">
                                        <template
                                            v-for="(
                                                subMenu2, subMenuIndex2
                                            ) in subMenu.subMenus"
                                        >
                                            <router-link
                                                v-if="
                                                    subMenu2.menuType ==
                                                    'single'
                                                "
                                                :key="`subMenu${index}_${subMenuIndex2}`"
                                                :to="subMenu2.url"
                                                v-slot="{
                                                    href,
                                                    navigate,
                                                    isActive,
                                                    isExactActive,
                                                }"
                                            >
                                                <li
                                                    v-canAccess:[subMenu2.directiveName]="
                                                        $getConst(
                                                            subMenu2.permissionName
                                                        )
                                                    "
                                                    aria-haspopup="true"
                                                    data-menu-toggle="hover"
                                                    class="menu-item"
                                                    :class="[
                                                        isActive &&
                                                            'menu-item-active',
                                                        isExactActive &&
                                                            'menu-item-active',
                                                    ]"
                                                >
                                                    <a
                                                        :href="href"
                                                        class="menu-link"
                                                        @click="navigate"
                                                    >
                                                        <i
                                                            class="menu-bullet menu-bullet-dot"
                                                        >
                                                            <span></span>
                                                        </i>
                                                        <span
                                                            class="menu-text"
                                                            >{{
                                                                subMenu2.label
                                                            }}</span
                                                        >
                                                    </a>
                                                </li>
                                            </router-link>
                                        </template>
                                    </ul>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </li>
        </template>
        <!-- Menu Array End Here -->
    </ul>
</template>

<script lang="ts">
import { Component, Mixins } from "vue-property-decorator";
import { menuItems } from "@/filters/common";
import CommonServices from "@/mixins/common";

@Component
class KTMenu extends Mixins(CommonServices) {
    menus = JSON.parse(JSON.stringify(menuItems));
    searchMenu = "";

    hasActiveChildren(match: string): boolean {
        return this.$route.path.indexOf(match) !== -1;
    }

    onSearchMenu(): void {
        let filter = this.searchMenu.toUpperCase();
        let clonnedMenus = JSON.parse(JSON.stringify(menuItems));
        if (filter) {
            this.menus = this.menus.filter((menu) => {
                if (menu.menuType == "nested") {
                    menu.subMenus = menu.subMenus?.filter((subMenu) => {
                        if (subMenu.menuType == "nested") {
                            subMenu.subMenus = subMenu.subMenus?.filter(
                                (subMenuu) =>
                                    subMenuu.label
                                        .toUpperCase()
                                        .includes(filter)
                            );
                            if (subMenu.subMenus.length > 0) {
                                return true;
                            }
                        }
                        return subMenu.label.toUpperCase().includes(filter);
                    });
                    if (menu.subMenus.length > 0) {
                        return true;
                    }
                } else {
                    return menu.label.toUpperCase().includes(filter);
                }
            });
        } else {
            this.menus = clonnedMenus;
        }
    }
}
export default KTMenu;
</script>
