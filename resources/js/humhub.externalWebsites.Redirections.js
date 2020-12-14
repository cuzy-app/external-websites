
humhub.module('externalWebsites.Redirections', function (module, require, $) {
    module.initOnPjaxLoad = false;

    // If Humhub is not embedded in an iframe and the current space has an URL to redirect
    if (window.self === window.top && module.config.urlToRedirect) {
        window.location.replace(module.config.urlToRedirect);
    }
});