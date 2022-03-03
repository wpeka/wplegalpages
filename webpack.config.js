/**
 * Config file for webpack.
 *
 * @link  https://wplegalpages.com/
 * @since 1.0.0
 *
 * @package Wplegalpages
 */
 const path = require('path');
 const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
 
 var vueconfig = {
     entry: {
         main: './src/vue-settings.js',
         
     },
     output: {
         path: path.resolve(__dirname, 'admin/js/vue'),
         filename: 'wplegalpages-admin-[name].js'
     },
     mode: 'development',
     resolve: {
         alias: {
             'vue$': 'vue/dist/vue.esm.js'
         }
     },
     module: {
         rules: [
             {
                 test: /\.css$/,
                 use: ['style-loader', 'css-loader']
             },
             {
                test: /\.(gif|svg|jpg|png)$/,
                use: ['file-loader']
            }
         ]
     }
 }

 module.exports = [
     vueconfig      
 ];