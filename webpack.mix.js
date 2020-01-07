const mix = require('laravel-mix');

// Allow multiple Laravel Mix applications
require('laravel-mix-merge-manifest');
mix.mergeManifest();

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/dashboard.scss', 'public/css')
    .version();
