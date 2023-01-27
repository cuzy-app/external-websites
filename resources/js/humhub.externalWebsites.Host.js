humhub.module('externalWebsites.Host', function (module, require, $) {
    module.initOnPjaxLoad = true;

    const init = function () {
        $(function () {
            // If theme has a left navigation menu (Clean theme)
            if (
                $('#ew-website').length
                && $('#left-navigation-expand-btn').length
                && typeof module.config.hideSidebar !== 'undefined'
                && module.config.hideSidebar
                && $(window).width() < 1400
            ) {
                setTimeout(function () {
                    humhub.modules.cleanTheme.leftNavigation.collapseMenu();
                }, 2500);
            }

            // If theme body has a sidebar (Enterprise theme)
            if ($('#wrapper').length) {

                // If sidebar is toggled, resize iframe container
                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.attributeName === "class") {
                            // var attributeValue = $(mutation.target).prop(mutation.attributeName);

                            // Resize after 1 seconds because of the 0.5 seconds transition CSS
                            setTimeout(function () {
                                document.getElementById('ew-page-container').iFrameResizer.resize();
                            }, 1000);
                        }
                    });
                });
                observer.observe($("#wrapper")[0], {
                    attributes: true
                });

                // Hide sidebar if needed
                if (typeof module.config.hideSidebar !== 'undefined' && module.config.hideSidebar) {
                    if ($(window).width() < 768) {
                        $('#wrapper').removeClass('toggled');
                    } else {
                        $('#wrapper').addClass('toggled');
                    }
                }
            }
        });
    };

    var updateBrowserUrlAndToggleSidebar = function () {
        // Update browser URL
        if (history.pushState) {
            window.history.pushState(null, '', module.config.permalink);
        } else {
            window.history.replaceState(null, '', module.config.permalink);
        }
    };

    // Executed by views/page/index.php in the iframe tag. See https://github.com/davidjbradshaw/iframe-resizer/issues/443#issuecomment-331721886
    var loadIFrameResizer = function () {
        // set global vars
        var iframeMessage;

        // When IframeResize plugin is loaded (in resources folder)
        iFrameResize(
            {
                log: false,
                scrolling: true, // if iframed page has not the content window javascript
                inPageLinks: true,

                // Each time iframed page is loaded or URL changes
                onMessage: function (messageData) {
                    // sroll top
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);

                    // message sent by iframed page is : {
                    //   url: window.location.href,
                    //   title: document.getElementsByTagName("title")[0].innerText
                    // }
                    iframeMessage = messageData.message; // update global var

                    // Remove scrollbar
                    $('#ew-page-container').attr('scrolling', 'no');

                    // Load comments with ajax, after the iframe tag, each time URL changes in the iframed website
                    $.pjax.reload('#ew-page-addons', {
                        type: 'POST',
                        url: module.config.pageActionUrl,
                        push: false,
                        replace: false,
                        data: {
                            pageUrl: iframeMessage.pageUrl,
                            pageTitle: iframeMessage.pageTitle,
                            showComments: (iframeMessage.showComments ? 1 : 0),
                            showLikes: (iframeMessage.showLikes ? 1 : 0),
                            showPermalink: (iframeMessage.showPermalink ? 1 : 0)
                        }
                    });
                },
            },
            '#ew-page-container'
        );
    };

    module.export({
        init: init,
        updateBrowserUrlAndToggleSidebar: updateBrowserUrlAndToggleSidebar,
        loadIFrameResizer: loadIFrameResizer
    });
});