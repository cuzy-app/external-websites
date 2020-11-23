
humhub.module('iframe', function (module, require, $) {
    module.initOnPjaxLoad = true;

    // set global vars
    var iframeMessage;

    // Wait for elements to be loaded
    var init = function(isPjax) { 
        // Do some global initialization work, which needs to run in any case
        $(function() {

            // If theme body has a sidebar (Enterprise theme)
            if ($('#wrapper').length) {

                // If sidebar is toggled, resize iframe
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === "class") {
                            // var attributeValue = $(mutation.target).prop(mutation.attributeName);

                            // Resize after 1 seconds because of the 0.5 seconds transition CSS
                            setTimeout(function(){
                                document.getElementById('iframe-container').iFrameResizer.resize();
                            },1000);
                        }
                    });
                });
                observer.observe($("#wrapper")[0], {
                    attributes: true
                });

                // Hide sidebar if needed
                if (typeof hideSidebar !== 'undefined' && hideSidebar) {
                    $('#wrapper').addClass('toggled');
                    hideSidebar = false; // can be reactivated by `url-content.php`
                }
            }
        });
    }

    // Executed by views/page/index.php in the iframe tag. See https://github.com/davidjbradshaw/iframe-resizer/issues/443#issuecomment-331721886
    var loadIFrameResizer = function () {
        // When IframeResize plugin is loaded (in resources folder)
        iFrameResize(
            {
                log: false,
                scrolling: true, // if iframed website has not the content window javascript
                inPageLinks: true,

                // Each time iframed website has loaded the content window javascript
                onInit: function(messageData) {
                },

                // Each time iframed page is loaded or URL changes
                onMessage: function(messageData) {
                    // sroll top
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);

                    // message sent by iframed website is : {
                    //   url: window.location.href,
                    //   title: document.getElementsByTagName("title")[0].innerText
                    // }
                    iframeMessage = messageData.message; // update global var

                    // Remove scrollbar
                    $('#iframe-container').attr('scrolling', 'no');

                    // Load comments with ajax, after the iframe tag, each time URL changes in the iframed website
                    $.pjax.reload('#iframe-comments', {
                        type : 'POST',
                        url: urlContentActionUrl,
                        push: false,
                        replace: false,
                        data: {
                            containerPageId: $('#iframe-page').attr('data-container-page-id'),
                            iframeMessage: iframeMessage,
                        }
                    });
                },
            },
            '#iframe-container'
        );
    };


    module.export({
        init: init,
        loadIFrameResizer: loadIFrameResizer
    });
});