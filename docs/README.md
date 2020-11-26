# External Websites


## Overview

Creates a content for each external website page, enabling to have Humhub addons (comments, like, files, etc.) in theses pages.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Usage

2 possibilities to use this module:

### Embed external website in Humhub

**Humhub is host, external website is guest.**

Upload theses files on the external website server:
```
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.contentWindow.min.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.contentWindow.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.contentWindow.map
```

And add this code just before `</body>` in all pages :
```
    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>

    <script type="text/javascript">
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
                    pageUrl: location.href.replace(location.hash,""),
                    pageTitle: document.getElementsByTagName("title")[0].innerText
                });
            }
        }
    </script>
```

For CODIMD in a docker, [see this documentation](https://gitlab.com/funkycram/doc/-/wikis/CodiMd#add-humhub-iframe-module-script-using-dockerfile)


### Embed Humhub addons (comments, like, files, etc.) in an external website

**Humhub is guest, external website is host.**

You must have something to auto log (and auto register if no account) the user.

Allow Humhub to be embeded in an iframe: edit `proteced/config/web.php` and in the `modules` section, add:
```
        'web' => [
            'security' =>  [
                "headers" => [
                    "Strict-Transport-Security" => "max-age=31536000",
                    "X-XSS-Protection" => "1; mode=block",
                    "X-Content-Type-Options" => "nosniff",
                    "Referrer-Policy" => "no-referrer-when-downgrade",
                    "X-Permitted-Cross-Domain-Policies" => "master-only",
                    "X-Frame-Options" => "sameorigin",
                    "Content-Security-Policy" => "default-src *; connect-src  *; font-src 'self'; frame-src https://* http://* *; img-src https://* http://* * data:; object-src 'self'; script-src 'self' https://* http://* * 'unsafe-inline' 'report-sample'; style-src * https://* http://* * 'unsafe-inline'; frame-ancestors 'self' https://my-external-website.tdl;"
                ]
            ]
        ],
```
And replace `https://my-external-website.tdl` with your website URL
If doesn't work, replace `"X-Frame-Options" => "sameorigin",` with `"X-Frame-Options" => "",`


Code for the website integrating Humhub comments (do not replace params values `0` or `1` with `true` or `false`) :
```
<?php 
$humhubWebsiteId = 1;
$currentPageUrl = 'http://my-website-integrating-humhub-comments.tdl/my-page.php';
$currentPageTitle = 'Page title';
?>
<iframe src="http://y-humhub.tdl/s/my-space/external-websites/page?websiteId=<?= $humhubWebsiteId ?>&pageUrl=<?= urlencode($currentPageUrl) ?>&pageTitle=<?= urlencode($currentPageTitle) ?>&humhubIsHost=0&autoLogin=1&addToSpaceMembers=1&addGroupRelatedToSpace=1"></iframe>
```


## Special features

It is possible to have several websites of the same external website in the same space.

In this case, Humhub addons (comments, like, files, etc.) are shared with the websites and the Humhub addons will be related to the website having the smaller `sort_order`.