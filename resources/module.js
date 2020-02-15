// set global vars
var iframeModal;
var iframeUrl;

humhub.module('iframe', function (module, require, $) {
    module.initOnPjaxLoad = true;

    var init = function(isPjax) { 
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



function loadNewUrlContent (iframeUrl) {
    $.ajax({
        method: "POST",
        url: urlContentActionUrl,
        data: {
            containerPageId: $('#iframe-page').attr('data-container-page-id'),
            url: iframeUrl,
        },
        success: function(data) {
            $('#iframe-comments').html(data);
        },
    }).done(function(msg) {
    });
}


function loadIframeComments (commentsUrl) {
    iframeModal.global.load(commentsUrl).then(function(response) {
        $(this).on('hide.bs.modal', function (e) {
            loadNewUrlContent (iframeUrl);
        })
    }).catch(function(e) {
        module.log.error(e, true);
    });
}