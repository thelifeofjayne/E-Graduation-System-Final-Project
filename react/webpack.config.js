const debug = process.env.NODE_ENV !== 'production'
const webpack = require('webpack')
const path = require('path')

const APP_DIR = path.resolve(__dirname, './src')
const BUILD_DIR = path.resolve(__dirname, './public')

const CompressionPlugin = require("compression-webpack-plugin")

const config = {
    entry: APP_DIR + '/index.js',
    module: {
        loaders: [{
            test: /\.jsx?$/,
            exclude: /node_modules/,
            loader: 'babel-loader',
            query: {
                presets: ['react', 'es2015', 'stage-0'],
                plugins: ['react-html-attrs', 'transform-class-properties', 'transform-decorators-legacy'],
            }
        }]
    },
    node: {
        fs: "empty",
        child_process: 'empty'
    },
    output: {
        path: BUILD_DIR,
        filename: 'app.min.js'
    },
    plugins: debug ? [] : [
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false,
                pure_getters: true,
                unsafe: true,
                unsafe_comps: true,
                screw_ie8: true
            },
            output: {
                comments: false
            }
        }),
        new webpack.ContextReplacementPlugin(/moment[\/\\]locale$/, /de/),
// new webpack.NoEmitOnErrorsPlugin(),
        // new CompressionPlugin({
        //     asset: "[path].gz[query]",
        //     algorithm: "gzip",
        //     test: /\.js$|\.css$|\.html$/,
        //     threshold: 10240,
        //     minRatio: 0
        // }),
        // new webpack.optimize.AggressiveMergingPlugin(),
    ]
};

module.exports = config;
