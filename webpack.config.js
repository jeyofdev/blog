const webpack = require('webpack')
const path = require('path')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')


// plugins
const plugins = {
    extractCss: require('mini-css-extract-plugin'),
    html: require('html-webpack-plugin'),
    Manifest: require('webpack-manifest-plugin'),
    styleLint: require('stylelint-webpack-plugin'),
    copy: require('copy-webpack-plugin')
}


// the loaders to the css
let cssLoaders = [
    { 
        loader: plugins.extractCss.loader,
        options: {
            publicPath: '../',
        }
    },
    { loader: 'css-loader', options: { importLoaders: 1 } },
    'resolve-url-loader',
]



module.exports = (env, argv) => {
    // check that we are in development or production mode
    let dev = env.development ? true : false

    // apply postcss-loader only in production mode
    if (!dev) { cssLoaders.push('postcss-loader') }

    let config = {
        entry: {
            app: [
                './public-dev/assets/js/app.js',
                './public-dev/assets/scss/app.scss',
                './public-dev/assets/css/app.css'
            ]
        },
        output: {
            path: path.resolve(__dirname, './public'),
            filename: (dev) ? 'js/[name].js' : 'js/[name]-[hash:8].js'
        },
        devtool: (dev) ? 'source-map' : 'cheap-module-eval-source-map',
        resolve: {
            alias: {
                '@js': path.resolve(__dirname, './public-dev/assets/js/'),
                '@scss': path.resolve(__dirname, './public-dev/assets/scss/'),
                '@css': path.resolve(__dirname, './public-dev/assets/css/')
            }
        },
        watch: dev,
        devServer: {
            port: 8800,
            overlay: true,
            contentBase: path.resolve(__dirname, './public')
        },
        module: {
            rules: [
                {
                    enforce: 'pre',
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'eslint-loader'
                    }
                },
                {
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'babel-loader'
                    }
                },
                {
                    test: /\.css$/,
                    use: cssLoaders
                },
                {
                    test: /\.scss$/,
                    use: [
                        ...cssLoaders,
                        'sass-loader'
                    ]
                },
                {
                    test: /\.(png|jpe?g|gif|svg)$/i,
                    use: [
                        {
                            loader: 'url-loader',
                            options: {
                                limit: 8192,
                                name: (dev) ? 'img/[name].[ext]' : 'img/[name]-[hash:8].[ext]'
                            }
                        },
                        {
                            loader: 'image-webpack-loader',
                            options: {
                                    bypassOnDebug: dev,
                                    mozjpeg: {
                                    progressive: true,
                                    quality: 65
                                },
                                optipng: {
                                    enabled: false
                                },
                                pngquant: {
                                    quality: [0.65, 0.90],
                                    speed: 4
                                },
                                gifsicle: {
                                    interlaced: false
                                }
                            }
                        }
                    ]
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)$/,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: (dev) ? 'fonts/[name].[ext]' : 'fonts/[name]-[hash:8].[ext]'
                            }
                        }
                    ]
                },
                {
                    test: /\.(html)$/,
                    use: {
                        loader: 'html-loader',
                        options: {
                        minimize: !dev,
                        removeComments: !dev
                        }
                    }
                }
            ]
        },
        plugins: [
            new CleanWebpackPlugin({
                dry: false,  // set to true to verify that the correct files are targeted
                verbose: true,
            }),
            new plugins.html({
                template: './public-dev/index.html',
                filename: 'index.html',
            }),
            new plugins.extractCss({
                filename: (dev) ? 'css/[name].css' : 'css/[name]-[hash:8].css'
            }),
            new plugins.styleLint(),
            new plugins.copy([
                './public-dev/index.php',
            ])
        ]
    }

    if(!dev){
        config.plugins.push(new plugins.Manifest({
            fileName: 'manifest.json'
        }))
    }

    return config
}