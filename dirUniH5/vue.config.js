const webpack = require("webpack");
const TransformPages = require('uni-read-pages');
const tfPages = new TransformPages({
    includes:['path','name','meta', 'authType', 'data']
});
module.exports = {
    transpileDependencies:['uni-simple-router'],
    configureWebpack: {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                jquery: 'jquery',
                'window.$': 'jquery',
                'window.jquery': 'jquery',
                'window.jquery': 'jquery',
                Popper: ['popper.js', 'default']
            }),
            new tfPages.webpack.DefinePlugin({
                ROUTES: JSON.stringify(tfPages.routes)
            })
        ]
    }
};