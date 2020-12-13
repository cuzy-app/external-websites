# External Websites


## Overview

Creates a content for each external website page, enabling to have Humhub addons (comments, like, files, etc.) in theses pages.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Features

TBD

## Usage

The module must be activated in a space. Then, in the space header control menu, you can add some websites.

For each website added, there are 2 possibilities:


### Embed external website in Humhub

**Humhub is host (parent), external website is embedded in Humhub within an iframe.**

Upload these files on the external website server:
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


### Embed Humhub addons (comments, like, files, etc.) in an external website

**Humhub is embedded, external website is host (parent). Humhub addons are in an iframe.**

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


Upload these files on the external website server:
```
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.min.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.map
```


Code for the website integrating Humhub comments:
```
<?php 
// String space URL (In space managment, "advanced" tab)
$spaceUrl = 'my-space';
// Integer - Humhub Website ID (get this value from the "Websites managment" page)
$humhubWebsiteId = 1;
// String - This page URL
$currentPageUrl = 'http://my-website-integrating-humhub-addons.tdl/my-page.php';
// String - This page title (usually the value in the <title> tag)
$currentPageTitle = 'Page title';
// Boolean (1 or 0) - Auto login (available if the module `authclients-addon` is installed and SSO is configured)
$autoLogin = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the related space members, so that he can comment the pages
$addToSpaceMembers = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the group members related to the space
$addGroupRelatedToSpace = 1;
?>

<style type="text/css">
    iframe#humhub-addons {
        width: 100%;
    }
</style>

<!-- Where you want to show the comments -->
<iframe id="humhub-addons" src="http://my-humhub.tdl/s/<?= $spaceUrl ?>/external-websites/page?websiteId=<?= $humhubWebsiteId ?>&pageUrl=<?= urlencode($currentPageUrl) ?>&pageTitle=<?= urlencode($currentPageTitle) ?>&autoLogin=<?= $autoLogin ?>&addToSpaceMembers=<?= $addToSpaceMembers ?>&addGroupRelatedToSpace=<?= $addGroupRelatedToSpace ?>"></iframe>

<!-- Just before </body> -->
<script type="text/javascript" src="path-to-js-files/iframeResizer.min.js"></script>
<script type="text/javascript">
    iFrameResize({
        log: false,
        scrolling: true,
    }, '#humhub-addons');
</script>
```



## Advanced features


### Specific behaviors

It is possible to have several websites of the same external website in the same space. In this case, Humhub addons (comments, like, files, etc.) are shared with the websites and the Humhub addons will be related to the website having the smaller `sort_order`.

If the content related to a page is archived and all comments have been removed, only the permalink will be shown


### Auto login and auto add user to space and group

If Humhub is embedded:
- If the module `authclients-addon` is installed, the module can try to auto login with SSO (if user doesn't exists, the account is created automatically)
- After login, the module can add the user to the related space members and to the space's related group members


### Authentification with JWT

If Humhub is embedded, it is possible to ask Humhub to check if the external website is authorized.

In that case, you must add a HS512 secret key in `proteced/config/common.php` (any 84 characters string):
```
    'modules' => [
        'external-websites' => [
            'jwtKey' => 'your-512-bit-secret',
        ],
    ],
```
To generate a random secret key, you can go to this URL : `https://your-humhub-site.tdl/external-websites/admin/generate-secret-key`

To get the token, in https://jwt.io/:
- Select algorithm HS512
- In PAYLOAD:DATA, replace with (in this example, 1 is the website ID):
```
{
 "website_id": 1
}
```
- VERIFY SIGNATURE: replace `your-512-bit-secret` by the secret key and uncheck `secret base64 encoded`
- Copy the encoded token

If the iframe `src` attribute, you must add this to the URL:
```
&website_id=your-encoded-token
```