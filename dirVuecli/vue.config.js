// vue.config.js
// const path = require('path');
const webpack = require("webpack");
module.exports = {
    publicPath:"./",
    // assetsDir:"assets",
    // indexPath:"index.html",
    // filenameHashing:true,
    // pages:undefined,
    // lintOnSave:true,
    // runtimeCompiler:false,
    // transpileDependencies:[],
    // productionSourceMap:false,
    // crossorigin:undefined,
    // integrity:false,
    outputDir:"dist",
    configureWebpack: {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                jquery: 'jquery',
                'window.jQuery': 'jquery',
                'window.jquery': 'jquery',
                Popper: ['popper.js', 'default']
            })
        ]
    }
};