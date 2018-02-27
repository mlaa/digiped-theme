const url = require('url');
const webpack = require('webpack');
const BrowserSyncPlugin = require('browsersync-webpack-plugin');
const WebpackShellPlugin = require('./util/webpackShellPlugin');

const config = require('./config');

const target = process.env.DEVURL || config.devUrl;

/**
 * We do this to enable injection over SSL.
 */
if (url.parse(target).protocol === 'https:') {
  process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;
}

module.exports = {
  output: {
    pathinfo: true,
    publicPath: config.proxyUrl + config.publicPath,
    //publicPath: config.devUrl + config.publicPath,
  },
  devtool: '#cheap-module-source-map',
  stats: false,
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoEmitOnErrorsPlugin(),
    new BrowserSyncPlugin({
      target,
      open: config.open,
      proxyUrl: config.proxyUrl,
      watch: config.watch,
      delay: 500,
    }),
    new WebpackShellPlugin({
         //onBuildStart: ['yarn build'],
         onBuildEnd: ['yarn build && browser-sync -u https://localhost:3000 reload']
    }),
  ],
};

if (module.hot) { module.hot.accept(); }
