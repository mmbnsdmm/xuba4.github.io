const webpack = require("webpack");
module.exports = {
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
            })
        ]
    }
};