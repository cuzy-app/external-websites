// set global vars
var iframeModal;
var iframeMessage;

humhub.module('iframe', function (module, require, $) {
    module.initOnPjaxLoad = true;

    // Wait for elements to be loaded
    var init = function(isPjax) { 
        
        // When IframeResize plugin is loaded (in resources folder)
        iFrameResize(
            {
                log: false,
                scrolling: false, // if iframed website has not the content window javascript
                inPageLinks: true,

                // Each time iframed website has loaded the content window javascript
                onInit: function(messageData) {
                    // Remove scrollbar
                    $('#iframe-page').attr('scrolling', 'no');
                },

                // Each time iframed page is loaded or URL changes
                onMessage: function(messageData) {
                    // message sent by iframed website is : {
                    //   url: window.location.href,
                    //   title: document.getElementsByTagName("title")[0].innerText
                    // }
                    iframeMessage = messageData.message; // update global var
                    // Load new ajax content related to the iframe website URL (comments)
                    loadNewUrlContent ();
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
function loadNewUrlContent () {
    $.ajax({
        method: "POST",
        url: urlContentActionUrl,
        data: {
            containerPageId: $('#iframe-page').attr('data-container-page-id'),
            iframeMessage: iframeMessage,
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
            loadNewUrlContent ();
        })
    }).catch(function(e) {
        module.log.error(e, true);
    });
}