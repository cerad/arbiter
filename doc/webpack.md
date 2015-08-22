
http://www.whitewashing.de/2015/02/26/integrate_symfony_and_webpack.html#disqus_thread
https://blog.risingstack.com/the-react-way-getting-started-tutorial/
http://jmfurlott.com/tutorial-setting-up-a-single-page-react-web-app-with-react-router-and-webpack/

### Install nodejs if necessary
$ npm --version
2.11.3

$ npm init # To make package.json

# Ignored assorted warnings
npm install --save-dev webpack  
npm install --save-dev webpack-dev-server  # problem with python version?

npm install --save-dev babel  
npm install --save-dev babel-loader  

npm install --save-dev react
npm install --save-dev react-router
npm install --save-dev react-hot-loader

# Just to make things a bit easier
npm install -g webpack

Created webpack.config.js from jmfurlott.com
Created empty app.js
Running webpack created web/assets/bundle.js with react

From integrate_symfony_and_webpack
webpack-dev-server --progress --colors --port 8090 --content-base=web/

Cannot get this to work
http://localhost:8090/app.php/
Need to figure out how to get app.php to run as php
Think I need a proxy of some sort

Took these lines out to disable hot module stuff

      { test: /\.js?$/, loaders: ['react-hot', 'babel'], exclude: /node_modules/ },
    'webpack/hot/only-dev-server',

Now testing with php -S localhost:8080 app-router.php
And just running webpack --watch from the command line to rebuild bundle.js

--watch did not work when I had an error?
