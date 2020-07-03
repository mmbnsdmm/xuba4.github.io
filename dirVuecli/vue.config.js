const webpack = require("webpack");
module.exports = {
    publicPath:"./",
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