const mix = require('laravel-mix');

// Allow multiple Laravel Mix applications
require('laravel-mix-merge-manifest');
mix.mergeManifest();

mix.sass('resources/sass/dashboard.scss', 'public/css')
    .js('resources/js/dashboard.js', 'public/js')
    .version();
