var webpack = require('webpack');

module.exports = {
  entry: [
    "./src-client/app.js"
  ],
  output: {
    path: __dirname + '/web/assets',
    filename: "bundle.js"
  },
  module: {
    loaders: [
      { test: /\.js$/, exclude: /node_modules/, loader: 'babel-loader'},
      { test: /\.css$/, loader: "style!css" }
    ]
  },
  plugins: [
    // new webpack.NoErrorsPlugin()
  ]

};
