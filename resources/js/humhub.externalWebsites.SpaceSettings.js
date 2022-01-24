humhub.module('externalWebsites.SpaceSettings', function (module, require, $) {
    module.initOnPjaxLoad = true;

    // If Humhub is not embedded in an iframe
    if (window.self === window.top) {
        // If the current space has a URL to redirect
        if (module.config.urlToRedirect) {
            window.location.replace(module.config.urlToRedirect);
        }
    }
    // If Humhub is embedded in an iframe
    else {
        if (module.config.preventLeavingSpace) {
            $('html').on('click', 'a[href*="/u/"], #activityContents a', function (e) {
                e.preventDefault();
            });
        }
    }
});