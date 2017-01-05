const webpack           = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const autoprefixer      = require('autoprefixer');
const cssnano           = require('cssnano');

let plugins = [];

module.exports = {
    entry: [ 
        'bootstrap-loader/extractStyles',
        './resources/assets/js/app.js',
    ],
    output: {
        filename: './js/app.js',
    },
    module: {
        loaders: [
            { 
                test: /\.css$/,
                exclude: /node_modules|dist/,
                loader: ExtractTextPlugin.extract({ 
                    fallbackLoader: "style-loader",
                    loader: "css-loader", }),
            },
            {
                test: /\.js$/,
                exclude: /node_modules|dist/,
                loader: 'babel-loader',
            },
            {
                test: /\.(woff2?|svg)$/,
                exclude: /dist/,
                loader: 'file?name=./dist/fonts/[name].[ext]',
            },
            {   
                test: /\.(ttf|eot)$/,
                exclude: /dist/,
                loader: 'file?name=./dist/fonts/[name].[ext]',
            },
        ],
    },
    plugins: [
        new ExtractTextPlugin('./css/app.css') ,
        new webpack.ProvidePlugin({
            $: 'jquery',
           'jQuery': 'jquery',
        })
    ],
    postcss: function() {
        return [
            autoprefixer({ browsers: [ '>2%' ] }),
        ];
    },
};

