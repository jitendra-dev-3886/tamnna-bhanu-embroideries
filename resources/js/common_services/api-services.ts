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

HTTP.interceptors.response.use(
    (response) => response,
    (error) => Promise.reject(error)
);
export default HTTP;
