const mix = require("laravel-mix");
const webpack = require("webpack");
const path = require("path");
const ForkTsCheckerWebpackPlugin = require("fork-ts-checker-webpack-plugin");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.disableNotifications();
mix.ts("resources/js/app.ts", "public/js").vue();
mix.sass("resources/sass/app.scss", "public/css");
mix.copyDirectory("resources/assets/images", "public/images");
mix.copyDirectory("resources/assets/media", "public/media");

//temporary samples folder is not needed/used but later for importing csv files it may be used as they are stored there
//mix.copyDirectory("resources/assets/samples", "public/samples");
mix.babelConfig();
mix.webpackConfig({
    output: {
        chunkFilename: "js/[name].[chunkhash].js"
    },
    resolve: {
        // We need to register the `.ts` extension so Webpack can resolve
        // TypeScript modules without explicitly providing an extension.
        // The other extensions in this list are identical to the Mix
        // defaults.
        extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"],
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
            "assets@": path.resolve(__dirname, "resources/assets")
        }
    },
    module: {
        /**
         * rule added to handle download csv
         */
        rules: [
            {
                test: /\.(csv|xlsx|xls)$/,
                loader: "file-loader",
                options: {
                    name: "csv/[name].[ext]"
                }
            },
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                options: {
                    transpileOnly: true, //1:55:24
                    appendTsSuffixTo: [/\.(ts|vue)$/]
                },
                exclude: /node_modules/
            }
        ]
    },
    plugins: [
        new webpack.IgnorePlugin({ resourceRegExp: /\.\/locale$/ }),
        // To strip all locales except "en"
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new ForkTsCheckerWebpackPlugin({
            //     // eslint: {
            //     //     files: "./resources/**/*.{ts,tsx,js,jsx}", // required - same as command `eslint ./src/**/*.{ts,tsx,js,jsx} --ext .ts,.tsx,.js,.jsx`
            //     // }, To show errors in terminal
        })
    ]
});
