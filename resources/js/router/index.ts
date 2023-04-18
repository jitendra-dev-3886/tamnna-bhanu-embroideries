import Vue from "vue";
import VueRouter, { RouteConfig } from "vue-router";
import { ConfigModule } from "../store/config";
import { UserModule } from "../store/user";
import { PermissionModule } from "../store/permission";
Vue.use(VueRouter);
import { differenceInMinutes, getTime } from "date-fns";
import {IPermissionResponse} from "../../assets/types/permission";

const siteName = " - Admin Panel";
/* Create new instance of VueRouter */
const routes: RouteConfig[] = [
    {
        path: "/",
        component: () => import("../pages/auth/Auth.vue"),
        children: [
            {
                name: "login",
                path: "/",
                component: () => import("../pages/auth/Login.vue"),
                meta: {
                    title: `Login${siteName}`,
                },
            },
            {
                name: "Logoff",
                path: "/logoff",
                component: () => import("../pages/auth/Logoff.vue"),
                meta: {
                    title: `Logoff${siteName}`,
                },
            },
        ],
    },
    {
        path: "/",
        redirect: "/dashboard",
        component: () => import("../pages/layout/Layout.vue"),
        children: [
            {
                path: "users",
                name: "users",
                component: () => import("../pages/masters/user/User.vue"),
                children: [
                    {
                        path: "/",
                        name: "user-list",
                        component: () =>
                            import("../pages/masters/user/UserList.vue"),
                        meta: {
                            requiresAuth: true,
                            permission: "users",
                            title: `User${siteName}`,
                            pageTitle: "User Management",
                        },
                    },
                    {
                        path: "add",
                        name: "add-user",
                        component: () =>
                            import("../pages/masters/user/AddEditUser.vue"),
                        meta: {
                            requiresAuth: true,
                            permission: "users",
                            title: `User${siteName}`,
                            pageTitle: "User Management",
                        },
                    },
                    {
                        path: "edit/:id",
                        name: "edit-user",
                        component: () =>
                            import("../pages/masters/user/AddEditUser.vue"),
                        meta: {
                            requiresAuth: true,
                            permission: "users",
                            title: `User${siteName}`,
                            pageTitle: "User Management",
                        },
                    },
                ],
            },
            {
                path: "activity-log",
                name: "activity-log",
                component: () =>
                    import("../pages/masters/activity-log/ActivityLog.vue"),
                meta: {
                    requiresAuth: true,
                    title: `Activity Log${siteName}`,
                    pageTitle: "Activity Log",
                },
            },
            {
                path: "/role",
                name: "role",
                component: () => import("../pages/masters/role/Role.vue"),
                meta: {
                    requiresAuth: true,
                    permission: "roles",
                    title: `Role${siteName}`,
                    pageTitle: "Role Management",
                },
            },
            {
                path: "/permission",
                name: "permission",
                component: () =>
                    import("../pages/masters/permission/Permission.vue"),
                meta: {
                    requiresAuth: true,
                    permission: "permissions",
                    title: `Permission${siteName}`,
                    pageTitle: "Permission Management",
                },
            },
            {
                path: "masters",
                name: "masters",
                component: () => import("../pages/masters/Masters.vue"),
                children: [
                    // {
                    //     path: 'admin',
                    //     name: 'admin',
                    //     component: () => import('../components/masters/admin/Admin.vue'),
                    //     children: [
                    //         {
                    //             path: '/',
                    //             name: 'admin-list',
                    //             component: () => import('../components/masters/admin/AdminList.vue'),
                    //             meta: {
                    //                 requiresAuth: true,
                    //                 permission: 'users',
                    //                 title: `Admin${siteName}`,
                    //                 pageTitle: 'Admin Management',
                    //             },
                    //         },
                    //         {
                    //             path: 'add',
                    //             name: 'add-admin',
                    //             component: () => import('../components/masters/admin/AddEditAdmin.vue'),
                    //             meta: {
                    //                 requiresAuth: true,
                    //                 permission: 'users',
                    //                 title: `Admin${siteName}`,
                    //                 pageTitle: 'Admin Management',
                    //             },
                    //         },
                    //         {
                    //             path: 'edit/:id',
                    //             name: 'edit-admin',
                    //             component: () => import('../components/masters/admin/AddEditAdmin.vue'),
                    //             meta: {
                    //                 requiresAuth: true,
                    //                 permission: 'users',
                    //                 title: `Admin${siteName}`,
                    //                 pageTitle: 'Admin Management',
                    //             },
                    //         },
                    //     ]
                    // },
                    {
                        path: 'homebanner',
                        name: 'homebanner',
                        component: () => import('../pages/masters/homebanner/HomeBanner.vue'),
                        children: [
                          {
                            path: '/',
                            name: 'homebanner-list',
                            component: () => import('../pages/masters/homebanner/HomeBannerList.vue'),
                            meta: {
                              requiresAuth: true,
                              permission: 'homebanners',
                              title: `Home Banners${siteName}`,
                              pageTitle: 'Home Banners Management',
                            },
                          },
                          {
                              path: 'add',
                              name: 'add-home-banners',
                              component: () => import('../pages/masters/homebanner/AddEditHomeBanner.vue'),
                              meta: {
                                  requiresAuth: true,
                                  permission: 'homebanners',
                                  title: `Home Banners${siteName}`,
                                  pageTitle: 'Home Banners Management',
                              },
                          },
                          {
                              path: 'edit/:id',
                              name: 'edit-homebanner',
                              component: () => import('../pages/masters/homebanner/AddEditHomeBanner.vue'),
                              meta: {
                                  requiresAuth: true,
                                  permission: 'homebanners',
                                  title: `Home Banners${siteName}`,
                                  pageTitle: 'Home Banners Management',
                              },
                           },
                        ]
                      },
            {
              path: 'customer',
              name: 'customer',
              component: () => import('../pages/masters/customer/Customer.vue'),
              children: [
                {
                  path: '/',
                  name: 'customer-list',
                  component: () => import('../pages/masters/customer/CustomerList.vue'),
                  meta: {
                    requiresAuth: true,
                    permission: 'users',
                    title: `Customer${siteName}`,
                    pageTitle: 'Customer Management',
                  },
                },
                {
                    path: 'add',
                    name: 'add-customer',
                    component: () => import('../pages/masters/customer/AddEditCustomer.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'users',
                        title: `Customer${siteName}`,
                        pageTitle: 'Customer Management',
                    },
                },
                {
                    path: 'edit/:id',
                    name: 'edit-customer',
                    component: () => import('../pages/masters/customer/AddEditCustomer.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'users',
                        title: `Customer${siteName}`,
                        pageTitle: 'Customer Management',
                    },
                 },
              ]
            },
            {
                path: 'category',
                name: 'category',
                component: () => import('../pages/masters/category/Category.vue'),
                children: [
                  {
                    path: '/',
                    name: 'category-list',
                    component: () => import('../pages/masters/category/CategoryList.vue'),
                    meta: {
                      requiresAuth: true,
                      permission: 'categories',
                      title: `Category${siteName}`,
                      pageTitle: 'Category Management',
                    },
                  },
                  {
                      path: 'add',
                      name: 'add-category',
                      component: () => import('../pages/masters/category/AddEditCategory.vue'),
                      meta: {
                          requiresAuth: true,
                          permission: 'categories',
                          title: `Category${siteName}`,
                          pageTitle: 'Category Management',
                      },
                  },
                  {
                      path: 'edit/:id',
                      name: 'edit-category',
                      component: () => import('../pages/masters/category/AddEditCategory.vue'),
                      meta: {
                          requiresAuth: true,
                          permission: 'categories',
                          title: `Category${siteName}`,
                          pageTitle: 'Category Management',
                      },
                   },
                ]
              },

            {
              path: 'product',
              name: 'product',
              component: () => import('../pages/masters/product/Product.vue'),
              children: [
                {
                  path: '/',
                  name: 'product-list',
                  component: () => import('../pages/masters/product/ProductList.vue'),
                  meta: {
                    requiresAuth: true,
                    permission: 'products',
                    title: `Product${siteName}`,
                    pageTitle: 'Product Management',
                  },
                },
                {
                    path: 'add',
                    name: 'add-product',
                    component: () => import('../pages/masters/product/AddEditProduct.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'products',
                        title: `Product${siteName}`,
                        pageTitle: 'Product Management',
                    },
                },
                {
                    path: 'edit/:id',
                    name: 'edit-product',
                    component: () => import('../pages/masters/product/AddEditProduct.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'products',
                        title: `Product${siteName}`,
                        pageTitle: 'Product Management',
                    },
                 },
              ]
            },

            {
              path: 'order',
              name: 'order',
              component: () => import('../pages/masters/order/Order.vue'),
              children: [
                {
                  path: '/',
                  name: 'order-list',
                  component: () => import('../pages/masters/order/OrderList.vue'),
                  meta: {
                    requiresAuth: true,
                    permission: 'orders',
                    title: `Order${siteName}`,
                    pageTitle: 'Order Management',
                  },
                },
                {
                    path: 'add',
                    name: 'add-order',
                    component: () => import('../pages/masters/order/AddEditOrder.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'orders',
                        title: `Order${siteName}`,
                        pageTitle: 'Order Management',
                    },
                },
                {
                    path: 'edit/:id',
                    name: 'edit-order',
                    component: () => import('../pages/masters/order/AddEditOrder.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'orders',
                        title: `Order${siteName}`,
                        pageTitle: 'Order Management',
                    },
                 },
              ]
            },

            /*{
              path: 'cart',
              name: 'cart',
              component: () => import('../pages/masters/cart/Cart.vue'),
              children: [
                {
                  path: '/',
                  name: 'cart-list',
                  component: () => import('../pages/masters/cart/CartList.vue'),
                  meta: {
                    requiresAuth: true,
                    permission: 'carts',
                    title: `Cart${siteName}`,
                    pageTitle: 'Cart Management',
                  },
                },
                {
                    path: 'add',
                    name: 'add-cart',
                    component: () => import('../pages/masters/cart/AddEditCart.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'carts',
                        title: `Cart${siteName}`,
                        pageTitle: 'Cart Management',
                    },
                },
                {
                    path: 'edit/:id',
                    name: 'edit-cart',
                    component: () => import('../pages/masters/cart/AddEditCart.vue'),
                    meta: {
                        requiresAuth: true,
                        permission: 'carts',
                        title: `Cart${siteName}`,
                        pageTitle: 'Cart Management',
                    },
                 },
              ]
            },*/
        ],
            },
        ],
    },
    {
        path: "*",
        component: () => import("../components/PageNotFound.vue"),
        name: "PageNotFound",
        meta: {
            title: `Page Not Found${siteName}`,
        },
    },
];
const router = new VueRouter({
    mode: "history",
    routes,
});
router.beforeEach((to, from, next) => {
    if (
        localStorage.getItem("login-timestamp") &&
        localStorage.getItem("login-timestamp") != null &&
        localStorage.getItem("login-timestamp") != ""
    ) {
        const fromTime = getTime(new Date());
        const toTime = Number(localStorage.getItem("login-timestamp"));
        const diffMinutes = differenceInMinutes(fromTime, toTime);
        if (diffMinutes >= 1410) {
            UserModule.CLEAR_USER_DATA();
        }
    } else {
        UserModule.CLEAR_USER_DATA();
    }
    const { authorization } = UserModule.currentUserData;
    document.title = to.meta?.title;

    // reset config to initial state
    ConfigModule.resetLayoutConfig();

    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (authorization) {
            next();
        } else if (authorization == "") {
            UserModule.SET_DEFAULT_URL(to.fullPath);
            next("/logoff");
        } else {
            UserModule.SET_DEFAULT_URL(to.fullPath);
            next("/");
        }
    } else {
        // if (to.path != "/logoff" && authorization == "") {
        //     next("/logoff");
        //     return;
        // }
        next();
    }
});

router.beforeResolve((to, from, next) => {
    const permissionData: IPermissionResponse[] = PermissionModule.userPermissions;
    const access = "index";
    if (to.matched.some((record) => record.meta.permission)) {
        // eslint-disable-next-line max-len
        const permissionArray = permissionData.filter(
            (permission) => permission.name == to.meta?.permission
        );
        if (permissionArray.length > 0) {
            const subpermissionArray = permissionArray[0].sub_permissions;
            /*if (to.matched.some((record) => record.meta.subpermission)) {
                // eslint-disable-next-line max-len
                const subpermissionmain =
                    permissionArray[0].sub_permissions.filter(
                        (subPermissionMain) =>
                            subPermissionMain.name == to.meta?.subpermission
                    );
                if (subpermissionmain.length > 0) {
                    subpermissionArray = subpermissionmain[0].sub_permissions;
                } else {
                    next("/");
                    return;
                }
            }*/
            if (subpermissionArray.length > 0) {
                const subpermission = subpermissionArray.filter(
                    (subPermission) =>
                        subPermission.name == access &&
                        subPermission.is_permission == "1"
                );
                if (subpermission.length > 0) {
                    next();
                } else {
                    next("/users");
                    PermissionModule.SET_PERMISSION_DIALOGUE(true);
                }
            }
        } else {
            next("/");
        }
    } else {
        next();
    }
});
router.onError((error) => {
    if (/loading chunk \d* failed./i.test(error.message)) {
        window.location.reload();
    }
});
// // Loading chunk error
// router.onError((error) => {
//     const pattern = /Loading chunk (\d)+ failed/g;
//     const isChunkLoadFailed = error.message.match(pattern);
//     const targetPath = router["history"].pending.fullPath;
//     if (isChunkLoadFailed) {
//         router.replace(targetPath);
//     }
// });

export default router;
