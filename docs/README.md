# External Websites


## Overview

Creates a content for each external website page, enabling to have Humhub addons (comments, like, files, permalink) in theses pages.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Features

- Add Humhub addons to external website pages; 2 possibilities:
  - external website is embedded in Humhub
  - Humhub addons are embedded in external website 
- Space's contents redirected to external website 
- Humhub embedded in an external website


## Usage

### Add Humhub addons to external website pages 

Addons are: comments, like, files and permalink that are attached to a content.
Here, for each external website page, a content is created when a first comment is posted.

The module must be activated in a space. Then, in the space header control menu, you can add some websites.

For each website added, there are 2 possibilities:


#### Embed external website in Humhub

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


#### Embed Humhub addons in an external website

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
// Humhub URL (without / at the end)
$humhubUrl = 'https://www.my-humhub.tdl';
// String space URL (In space managment, "advanced" tab)
$spaceUrl = 'my-space';
// Integer - Humhub Website ID (get this value from the "Websites managment" page)
$humhubWebsiteId = 1;
// String - This page URL
$currentPageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// String - This page title (usually the value in the <title> tag)
$currentPageTitle = 'Page title';
// Boolean (1 or 0) - Auto login (available if the module `authclients-addon` is installed and SSO is configured)
$autoLogin = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the related space members, so that he can comment the pages
$addToSpaceMembers = 1;
// Boolean (1 or 0) - After login, if not already the case, add the user to the group members related to the space
$addGroupRelatedToSpace = 1;
// JWT token for this $humhubWebsiteId value (optional, see bellow "Authentification with JWT")
$token = '';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>My title</title>

	<style type="text/css">
		iframe#humhub-addons {
			width: 100%;
		}
	</style>
</head>
<body>

	<p>My page content</p>

	<!-- Where you want to show the addons -->
	<iframe id="humhub-addons" src="<?= $humhubUrl ?>/s/<?= $spaceUrl ?>/external-websites/page?websiteId=<?= $humhubWebsiteId ?>&pageUrl=<?= urlencode($currentPageUrl) ?>&pageTitle=<?= urlencode($currentPageTitle) ?>&autoLogin=<?= $autoLogin ?>&addToSpaceMembers=<?= $addToSpaceMembers ?>&addGroupRelatedToSpace=<?= $addGroupRelatedToSpace ?>&token=<?= $token ?>" style="min-height: 700px;"></iframe>

	<!-- Just before </body> -->
	<script type="text/javascript" src="js/iframeResizer.min.js"></script>
	<script type="text/javascript">
		const iframes = iFrameResize({
			log: false,
			scrolling: true,
			onInit: function() {
				// Remove min-height if iframe resizer has loaded (e.g. after SSO login)
				document.getElementById("humhub-addons").style.minHeight="auto";
				document.getElementById("humhub-addons").scrolling="no";
			}
		}, '#humhub-addons');
	</script>

</body>
</html>
```

### Humhub embedded in an external website

It is possible to add some specific scripts (javascript) to Humhub if embedded in an iframe.
In that case, in `proteced/config/common.php` add this parameter:
```
    'modules' => [
        'external-websites' => [
            'registerAssetsIfHumhubIsEmbedded' => true,
        ],
    ],
```

This will add 2 scripts:
- To add a class to the html tag to know if Humhub is in an iframe or not
- iframeResizer.contentWindow.js file enabling to the external website to resize the iframe containing Humhub (see https://github.com/davidjbradshaw/iframe-resizer)

On the external website:

Upload these files on the external website server:
```
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.min.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.js
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.map
```

Example of PHP file that can be used with the external website:
```
<?php
$humhubUrl = 'http://www.my-humhub.tdl/dashboard';

// If you want to make redirections work (see "Space's contents redirected to external website")
if (isset($_GET['humhubUrl'])) {
	$humhubUrl = urldecode($_GET['humhubUrl']);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>My title</title>

	<style type="text/css">
		iframe#humhub {
			width: 100%;
		}
	</style>
</head>
<body>

	<p>Humhub integration</p>

	<!-- Where you want to show Humhub -->
	<iframe id="humhub" src="<?= $humhubUrl ?>" style="min-height: 700px;"></iframe>

	<!-- Just before </body> -->
	<script type="text/javascript" src="js/iframeResizer.min.js"></script>
	<script type="text/javascript">
		const iframes = iFrameResize({
			log: false,
			scrolling: true,
			onInit: function() {
				// Remove min-height if iframe resizer has loaded (e.g. after SSO login)
				document.getElementById("humhub").style.minHeight="auto";
				document.getElementById("humhub").scrolling="no";
			}
		}, '#humhub');
	</script>

</body>
</html>
```


### Space's contents redirected to external website

If the module is activated in a space, in the settings, it is possible to activate contents redirections to external website (see "Humhub embedded in an external website").

Redirects only if the user arrives directly on the space URL.
It is still possible to navigate in the space if already in Humhub (PJax load).

{humhubUrl} will be replaced with the Humhub's source URL.

E.g https://www.my-external-website.tdl?humhubUrl={humhubUrl} value will redirect https://wwww.my-humhub.tdl/s/space-name/xxx to https://www.my-external-website.tdl?humhubUrl=https://wwww.my-humhub.tdl/s/space-name/xxx


## Advanced features

### Specific behaviors

It is possible to have several websites of the same external website in the same space. In this case, Humhub addons are shared with the websites and the Humhub addons will be related to the website having the smaller `sort_order`.

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