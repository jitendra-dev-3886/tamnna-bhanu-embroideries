/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import { UserModule } from "@/store/user";
require("../bootstrap");

const getCSRFToken = () => {
    const token: HTMLMetaElement | null = document.head.querySelector(
        'meta[name="csrf-token"]'
    );
    return token?.content;
};
const token: string | undefined = getCSRFToken();

window.axios.defaults.headers.common["Content-Type"] = "application/json";

if (token) {
    window.csrfToken = token;
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = window.csrfToken;
}

//window.axios.defaults.baseURL = "http://127.0.0.1:8000";
const HTTP = window["axios"];
const configURLs = [
    "/api/v1/login",
    "forgot-password",
    "reset-password",
    "register",
    "check-user-logged-in-or-not"
];
const publicConfigURLs = ["countries", "states", "cities", "hobbies"];
// eslint-disable-next-line consistent-return
HTTP.interceptors.request.use(
    (config) => {
        if (configURLs.some((r) => config.url.includes(r))) {
            return config;
        }
        if (
            config.method == "get" &&
            publicConfigURLs.some((r) => config.url.includes(r))
        ) {
            const authorizationtoken = UserModule.currentUserData.authorization;
            if (authorizationtoken) {
                config.headers.common.Authorization = `Bearer ${authorizationtoken}`;
            }
            return config;
        }

        const authorizationtoken = UserModule.currentUserData.authorization;

        if (!authorizationtoken) {
            if (config.params && config.params.noAuth) {
                return config;
            }
            window.location.href = "/";
        } else {
            config.headers.common.Authorization = `Bearer ${authorizationtoken}`;
            return config;
        }
    },
    (error) => Promise.reject(error)
);
let isRefreshing = false;
let failedQueue: any = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach((prom) => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });

    failedQueue = [];
};

HTTP.interceptors.response.use(
    (response) => response,
    function(err){
        const originalRequest = err.config;

        if (
            err.response.status === 427 &&
            originalRequest.url.includes("refreshing-tokens")
        ) {
            localStorage.clear();
            window.location.href = "/";
            return Promise.reject(err);
        }

        if (err.response.status === 401 && !originalRequest._retry) {
            if (isRefreshing) {
                return new Promise(function (resolve, reject) {
                    failedQueue.push({ resolve, reject });
                })
                    .then((token) => {
                        originalRequest.headers["Authorization"] =
                            "Bearer " + token;
                        return HTTP(originalRequest);
                    })
                    .catch((err) => {
                        return Promise.reject(err);
                    });
            }

            originalRequest._retry = true;
            isRefreshing = true;

            return new Promise(function (resolve, reject) {
                HTTP.post("/api/v1/refreshing-tokens", {
                    refresh_token: UserModule.currentUserData.refresh_token,
                })
                    .then((res) => {
                        originalRequest.headers.Authorization =
                            "Bearer " + res.data.access_token;
                        HTTP.defaults.headers.common["Authorization"] =
                            "Bearer " + res.data.access_token;
                        UserModule.SET_TOKENS(res.data);
                        processQueue(null, res.data.access_token);
                        resolve(HTTP(originalRequest));
                    })
                    .catch((err) => {
                        processQueue(err, null);
                        reject(err);
                    })
                    .then(() => {
                        isRefreshing = false;
                    });
            });
        }
        return Promise.reject(err);
    }
);
export default HTTP;
