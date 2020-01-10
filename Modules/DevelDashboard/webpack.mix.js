const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/js/dashboard.js', 'js/devel/dashboard.js')
    .sass(__dirname + '/Resources/sass/dashboard.scss', 'css/devel/dashboard.css')
    .copy(__dirname + '/Resources/sass/dashboard/line-awesome.min.css', '../../public/css/devel/line-awesome.min.css')
    .copyDirectory(__dirname + '/Resources/fonts', '../../public/css/fonts');

if (mix.inProduction()) {
    mix.version();
}