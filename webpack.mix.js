const mix = require('laravel-mix')

const autoload = {
   jquery: [ '$', 'jQuery', 'jquery']
}
mix.autoload(autoload)

mix.setPublicPath('public')

mix.postCss("./resources/css/styles.css", "public/css", [
        require("tailwindcss"),
   ])
   .version();


mix.copyDirectory('resources/img', 'public/img');
// mix.copyDirectory('public', '../../../public/vendor/uccello/module-designer');


