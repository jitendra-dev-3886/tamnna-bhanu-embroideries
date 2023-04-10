<template>
    <div class="topbar-item">
        <div
            id="kt_quick_user_toggle"
            class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
        >
      <span
          class="text-muted font-weight-bold font-size-base d-md-inline mr-1"
      >
        Hi,
      </span>
            <span
                class="text-dark-50 font-weight-bolder font-size-base d-md-inline mr-3 black-color"
            >
        {{ username }}
      </span>
            <span class="symbol symbol-35 symbol-light-success">
        <img
            alt="Pic"
            :src="picture"
        >
                <!--<span
                  v-if="true"
                  class="symbol-label font-size-h5 font-weight-bold text-uppercase"
                >
                  {{ nameInitials }}
                </span>-->
      </span>
        </div>

        <div
            id="kt_quick_user"
            ref="kt_quick_user"
            class="offcanvas offcanvas-right p-10"
        >
            <!--begin::Header-->
            <div
                class="offcanvas-header d-flex align-items-center justify-content-between pb-5"
            >
                <h3 class="font-weight-bold m-0">
                    Admin Profile
                </h3>
                <a
                    id="kt_quick_user_close"
                    href="#"
                    class="btn btn-xs btn-icon btn-light btn-hover-primary"
                >
                    <i class="ki ki-close icon-xs text-muted"/>
                </a>
            </div>
            <!--end::Header-->

            <!--begin::Content-->
            <perfect-scrollbar
                class="offcanvas-content pr-5 mr-n5 scroll"
                style="max-height: 90vh; position: relative;"
            >
                <!--begin::Header-->
                <div class="d-flex align-items-center mt-5">
                    <div class="d-flex flex-column">
                        <a
                            class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"
                        >
                            {{ username }}
                        </a>
                        <div class="text-muted mt-1" v-if="roleName">{{roleName}}</div>
                        <div class="navi mt-2  mb-2">
                            <a
                                href="#"
                                class="navi-item"
                            >
                <span class="navi-link p-0 pb-2">
                  <span class="navi-icon mr-1">
                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                      <!--begin::Svg Icon-->
                      <inline-svg
                          src="../media/svg/icons/Communication/Mail-notification.svg"
                      />
                        <!--end::Svg Icon-->
                    </span>
                  </span>
                  <span class="navi-text text-muted text-hover-primary">
                    {{ userEmail }}
                  </span>
                </span>
                            </a>
                        </div>

                        <router-link to="/role">
                            <button
                                v-index="$getConst('ROLES')"
                                class="btn btn-light-primary btn-bold w100  mb-2"
                            >
                                Role
                            </button>
                        </router-link>
                        <router-link to="/permission">
                            <button
                                v-index="$getConst('PERMISSIONS')"
                                class="btn btn-light-primary btn-bold w100"
                            >
                                Permission
                            </button>
                        </router-link>
                        <v-divider/>
                        <button
                            class="btn btn-light-primary btn-bold mb-2"
                            @click="changePasswordModal = true"
                        >
                            Change Password
                        </button>
                        <button
                            class="btn btn-light-primary btn-bold "
                            @click="logout"
                        >
                            Sign out
                        </button>
                    </div>
                </div>
                <!--end::Header-->
            </perfect-scrollbar>
            <!--end::Content-->
        </div>

        <!-- change password Modal -->
        <change-password v-model="changePasswordModal" />
    </div>
</template>

<style lang="scss" scoped>
#kt_quick_user {
    overflow: hidden;
}
</style>

<script lang="ts">
// import { LOGOUT } from "../../../../common_services/services/store/auth.module";
import KTLayoutQuickUser from '../../../../../assets/js/layout/extended/quick-user.js';
import KTOffcanvas from '../../../../../assets/js/components/offcanvas.js';
import ChangePassword from '@/components/auth/ChangePassword.vue';
import ErrorModal from '@/components/ErrorModal.vue';
import CommonServices from '@/mixins/common';
import Idle from '@/mixins/idle';
import Component, {mixins} from 'vue-class-component';
import {UserModule} from "@/store/user";

@Component({
    components: {
        ChangePassword,
        ErrorModal
    },
})
class KTQuickUser extends mixins(CommonServices, Idle) {
    changePasswordModal: boolean = false;
    username: string = '';
    nameInitials: string = '';
    userEmail: string = '';
    roleName: string = '';
    contact_number:string=''

    mounted() {
        this.userEmail = UserModule.currentUserData.email;
        console.log(this.userEmail,this.contact_number,this.username,this.roleName);
        this.contact_number=UserModule.currentUserData.contact_number;
        this.username = UserModule.currentUserData.username;
        this.nameInitials = this.username.charAt(0);
        this.roleName = UserModule.currentUserData.role ? UserModule.currentUserData.role.name : '';
        // Init Quick User Panel
        KTLayoutQuickUser.init(this.$refs.kt_quick_user);
    }

    closeOffcanvas() {
        new KTOffcanvas(KTLayoutQuickUser.getElement())['hide']();
    }

    get picture() {
      if (UserModule.currentUserData.profile != '') {
        return UserModule.currentUserData.profile;
      }
      return '/images/profile.png';
    }

    get currentUserData() {
        return UserModule.currentUserData;
    }
}

export default KTQuickUser;
</script>
