// When iFrameResizer is loaded
var iFrameResizerLoaded = false; // avoid loading twice (iFrameResizer bug)
var iFrameResizer = {
    onReady: function(message) {
        if (!iFrameResizerLoaded) {
            sendUrlToParentIframe();
            iFrameResizerLoaded = true;
        }
    }
};

// If URL changes without reloading page (ajax)
window.addEventListener('locationchange', function() {
    sendUrlToParentIframe();
});

// Send new URL to parent iframe
function sendUrlToParentIframe() {
    if ('parentIFrame' in window) {
        document.getElementsByTagName("html")[0].classList.add("in-iframe");
        window.parentIFrame.sendMessage({
            pageUrl: window.location.href.replace(window.location.hash,""),
            pageTitle: document.getElementsByTagName("title")[0].innerText,
            showComments: !(document.getElementsByTagName('head')[0].dataset.externalComments == "0"),
            showLikes: !(document.getElementsByTagName('head')[0].dataset.externalLikes == "0"),
            showPermalink: !(document.getElementsByTagName('head')[0].dataset.externalPermalink == "0")
        });
    }
}