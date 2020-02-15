// set global vars
var iframeModal;
var iframeUrl;

humhub.module('iframe', function (module, require, $) {
    module.initOnPjaxLoad = true;

    // Wait for elements to be loaded
    var init = function(isPjax) { 
        
        // When IframeResize plugin is loaded (in resources folder)
        iFrameResize(
            {
                log: false,
                scrolling: true, // if iframed website has not the content window javascript
                inPageLinks: true,

                // Each time iframed website has loaded the content window javascript
                onInit: function(messageData) {
                    // Remove scrollbar
                    $(this).attr('scrolling', 'no');
                },

                // Each time iframed page is loaded or URL changes
                onMessage: function(messageData) {
                    iframeUrl = messageData.message;
                    loadNewUrlContent (iframeUrl);
                },
            },
            '#iframe-page iframe'
        );

        iframeModal = require('ui.modal');
    }

    module.export({
        init: init
    });
});


// Load comments with ajax, after the iframe tag, each time URL changes in the iframed website
function loadNewUrlContent (url) {
    $.ajax({
        method: "POST",
        url: urlContentActionUrl,
        data: {
            containerPageId: $('#iframe-page').attr('data-container-page-id'),
            url: url,
        },
        success: function(data) {
            $('#iframe-comments').html(data);
        },
    }).done(function(msg) {
    });
}


// Load modal box with comments and form to post a new comment
function loadIframeComments (commentsUrl) {
    iframeModal.global.load(commentsUrl).then(function(response) {
        // When modal box is closed
        $(this).on('hide.bs.modal', function (e) {
            // Reload ajax with new comment(s)
            loadNewUrlContent (iframeUrl);
        })
    }).catch(function(e) {
        module.log.error(e, true);
    });
}