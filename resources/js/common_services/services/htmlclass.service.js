import objectPath from "object-path";
import { HTMLClassModule } from "../../store/htmlclass";

const HtmlClass = {
    config: null,

    init(config) {
        if (typeof config !== "undefined") {
            this.config = config;
        }

        // init base layout
        this.initLayout();

        // init header and subheader menu
        this.initHeader();
        this.initSubheader();

        // init aside and aside menu
        this.initAside();

        // init footer
        this.initFooter();

        // init themes
        this.initThemes();
    },

    /**
     * Init Layout
     */
    initLayout() {
        if (objectPath.has(this.config, "self.body.class")) {
            const _selfBodyClass = objectPath
                .get(this.config, "self.body.class")
                .toString();
            if (_selfBodyClass) {
                const bodyClasses = _selfBodyClass.split(" ");
                bodyClasses.forEach((cssClass) => {
                    HTMLClassModule.addBodyClassName(cssClass);
                });
            }
        }

        const bgImage = objectPath.get(
            this.config,
            "self.body.background-image"
        );
        if (typeof bgImage !== "undefined") {
            document.body.style.backgroundImage = `url(${bgImage})`;
        }

        // Offcanvas directions
        HTMLClassModule.addBodyClassName("quick-panel-right");
        HTMLClassModule.addBodyClassName("demo-panel-right");
        HTMLClassModule.addBodyClassName("offcanvas-right");

        // Properly close mobile header menu
        HTMLClassModule.removeBodyClassName("header-menu-wrapper-on");
    },

    /**
     * Init Header
     */
    initHeader() {
        // Fixed header
        if (objectPath.get(this.config, "header.self.fixed.desktop")) {
            HTMLClassModule.addBodyClassName("header-fixed");
            HTMLClassModule.addClassName({
                position: "header",
                className: "header-fixed",
            });
        } else {
            HTMLClassModule.addBodyClassName("header-static");
        }

        if (objectPath.get(this.config, "header.self.fixed.mobile")) {
            HTMLClassModule.addBodyClassName("header-mobile-fixed");
            HTMLClassModule.addClassName({
                position: "header_mobile",
                className: "header-mobile-fixed",
            });
        }

        if (objectPath.get(this.config, "header.menu.self.display")) {
            HTMLClassModule.addClassName({
                position: "header_menu",
                className: `header-menu-layout-${objectPath.get(
                    this.config,
                    "header.menu.self.layout"
                )}`,
            });

            // Menu
            if (objectPath.get(this.config, "header.menu.self.root-arrow")) {
                HTMLClassModule.addClassName({
                    position: "header_menu",
                    className: "header-menu-root-arrow",
                });
            }
        }
    },

    /**
     * Init Subheader
     */
    initSubheader() {
        // Fixed content head
        if (
            objectPath.get(this.config, "subheader.fixed") &&
            objectPath.get(this.config, "header.self.fixed.desktop")
        ) {
            HTMLClassModule.addBodyClassName("subheader-fixed");
        }

        if (objectPath.get(this.config, "subheader.display")) {
            HTMLClassModule.addBodyClassName("subheader-enabled");
        }

        if (objectPath.has(this.config, "subheader.style")) {
            HTMLClassModule.addBodyClassName(
                `subheader-${objectPath.get(this.config, "subheader.style")}`
            );
        }
    },

    /**
     * Init Aside
     */
    initAside() {
        // Reset aside class in body
        HTMLClassModule.removeBodyClassName("aside-enabled");
        HTMLClassModule.removeBodyClassName("aside-fixed");
        HTMLClassModule.removeBodyClassName("aside-static");
        HTMLClassModule.removeBodyClassName("aside-minimize");

        if (objectPath.get(this.config, "aside.self.display") !== true) {
            return;
        }

        // Add aside class enabled in body
        HTMLClassModule.addBodyClassName("aside-enabled");

        // Fixed Aside
        if (objectPath.get(this.config, "aside.self.fixed")) {
            HTMLClassModule.addBodyClassName("aside-fixed");
            HTMLClassModule.addClassName({
                position: "aside",
                className: "aside-fixed",
            });
        } else {
            HTMLClassModule.addBodyClassName("aside-static");
        }

        // Default fixed
        if (objectPath.get(this.config, "aside.self.minimize.default")) {
            HTMLClassModule.addBodyClassName("aside-minimize");
        }

        // Dropdown Submenu
        if (objectPath.get(this.config, "aside.menu.dropdown")) {
            HTMLClassModule.addClassName({
                position: "aside_menu",
                className: "aside-menu-dropdown",
            });
        }
    },

    /**
     * Init Footer
     */
    initFooter() {
        // Fixed header
        if (objectPath.get(this.config, "footer.fixed")) {
            HTMLClassModule.addBodyClassName("footer-fixed");
        }
    },

    /**
     * Import theme SCSS file based on the selected theme
     */
    initThemes() {
        if (objectPath.get(this.config, "header.self.theme")) {
            const theme = objectPath.get(this.config, "header.self.theme");
            import(`../../../sass/themes/layout/header/base/light.scss`);
        }

        if (objectPath.get(this.config, "header.menu.desktop.submenu.theme")) {
            const theme = objectPath.get(
                this.config,
                "header.menu.desktop.submenu.theme"
            );
            import(`../../../sass/themes/layout/header/menu/dark.scss`);
        }

        if (objectPath.get(this.config, "brand.self.theme")) {
            const theme = objectPath.get(this.config, "brand.self.theme");
            import(`../../../sass/themes/layout/brand/dark.scss`);
        }

        if (objectPath.get(this.config, "aside.self.theme")) {
            const theme = objectPath.get(this.config, "aside.self.theme");
            import(`../../../sass/themes/layout/aside/dark.scss`);
        }
    },
};

export default HtmlClass;
