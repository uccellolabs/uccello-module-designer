const mix = require('laravel-mix')

const autoload = {
   jquery: [ '$', 'jQuery', 'jquery']
}
mix.autoload(autoload)

mix.setPublicPath('public')

mix.js('./resources/js/script.js', 'public/js')
   .postCss("./resources/css/styles.css", "public/css", [
        require("tailwindcss"),
   ])
//    .version()

mix.after(() => {
    // Copy all compiled files into main project (auto publishing)
    mix.copyDirectory('public', '../../../public/vendor/uccello/module-designer');
});


