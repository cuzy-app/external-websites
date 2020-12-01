# External Websites


## Overview

Creates a content for each external website page, enabling to have Humhub addons (comments, like, files, etc.) in theses pages.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Usage

The module must be activated in a space. Then, in the space header controll menu, you can add some websites.

For each website added, there are 2 possibilities:

### Embed external website in Humhub

**Humhub is host, external website is guest and embedded in an iframe.**

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

**Humhub is guest, external website is host. Humhub addons are embedded in an iframe.**

You must have something to auto log (and auto register if no account) the user.

Allow Humhub to be embedded in an iframe: edit `proteced/config/web.php` and in the `modules` section, add:
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


Code for the website integrating Humhub comments:
```
<?php 
// Integer - Humhub Website ID (get this value from the "Websites managment" page)
$humhubWebsiteId = 1;
// String - This page URL
$currentPageUrl = 'http://my-website-integrating-humhub-comments.tdl/my-page.php';
// String - This page title (usually the value in the <title> tag)
$currentPageTitle = 'Page title';
// Boolean (1 or 0) - Auto login (available if the module `authclients-addon` is installed and SSO is configured)
$autoLogin = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the related space members, so that he can comment the pages
$addToSpaceMembers = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the group members related to the space
$addGroupRelatedToSpace = 1;
?>
<iframe src="http://my-humhub.tdl/s/my-space/external-websites/page?websiteId=<?= $humhubWebsiteId ?>&pageUrl=<?= urlencode($currentPageUrl) ?>&pageTitle=<?= urlencode($currentPageTitle) ?>&autoLogin=<?= $autoLogin ?>&addToSpaceMembers=<?= $addToSpaceMembers ?>&addGroupRelatedToSpace=<?= $addGroupRelatedToSpace ?>"></iframe>
```


## Advanced features

It is possible to have several websites of the same external website in the same space. In this case, Humhub addons (comments, like, files, etc.) are shared with the websites and the Humhub addons will be related to the website having the smaller `sort_order`.

If the content related to a page is archived and all comments have been removed, only the permalink will be shown

If Humhub is guest:
- If the module `authclients-addon` is installed, the module can try to auto login with SSO
- after login, the module can add the user to the related space members and to the space's related group members