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
            url: location.href.replace(location.hash,""),
            title: document.getElementsByTagName("title")[0].innerText
        });
    }
}