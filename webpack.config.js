var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/assets/')
    // public path used by the web server to access the output path
    .setPublicPath('/assets')
    //.addEntry('js/likes', './assets/js/likes.js')
    // .addEntry('app', './assets/js/app.js')
    .addStyleEntry('css/dashboard', ['./assets/css/dashboard.css'])
    .addStyleEntry('css/login', ['./assets/css/login.css'])
    .addStyleEntry('css/likes', ['./assets/css/likes.css'])
;

module.exports = Encore.getWebpackConfig();
