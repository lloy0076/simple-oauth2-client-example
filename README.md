# Simple Oauth2 Test Site

This simple Oauth2 Client was used to test a Laravel Passport Oauth2 server,
although it could be used to test any generic Oauth2 service.

# Build Pre-Requistes

* Node JS
* NPM
* Composer
* Webpack

Note that this uses webpack 2.X - and not webpack 1.X!

Optional build requirements:

* Yarn

# To Build

* Install the node modules with 'npm install' or 'yarn install'
* Install the composer modules with 'composer install'
* Run webpack with 'webpack --progress'

Note you will need to make a directory 'cache' which should be writable by the
web-server; this serves as Twig's cache directory.


# To Use

Setup the relevant environment variables in your '.env' file - follow the example '.env.example'.

Then simply point your browser at 'index.php'.

# References

Oauth2:

* https://oauth.net/2/

Modules / Tools used:

* https://laravel.com/docs/5.3/passport
* https://github.com/thephpleague/oauth2-client
* http://twig.sensiolabs.org/

Build tools:

* https://nodejs.org/ (includes NPM)
* https://getcomposer.org/
* https://yarnpkg.com/
* https://webpack.js.org/
