const mix = require('laravel-mix');

mix.setPublicPath('public');

mix.js('resources/js/app.js', 'js')
   .sass('resources/sass/app.scss', 'css')
   .browserSync({
        'proxy': 'http://framework-default-route-4.test/',
        'files': [
            './resources/views/**/*.twig',
        ]
   }); // Hot reloading
