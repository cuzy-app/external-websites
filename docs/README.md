# External Websites

> :warning: **This module is experimental!**

## Overview

Creates a content for each external website page, enabling to have HumHub addons (comments, like, files, permalink) in these pages.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).

## Features

- Add HumHub addons to external website pages; 2 possibilities:
    - external website is embedded in HumHub
    - HumHub addons are embedded in external website
- Space's content URL redirected to external website
- HumHub embedded in an external website

## Usage

### Add HumHub addons to external website pages

Addons are: comments, like, files and permalink that are attached to a content. Here, for each external website page, a content is created when a first comment is posted.

The module must be activated in a space. Then, in the space header control menu, you can add some websites.

For each website added, there are 2 possibilities:

#### Embed external website in HumHub

**HumHub is host (parent), external website is embedded in HumHub within an iframe.**

Upload these files on the external website server:
```
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.contentWindow.min.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.contentWindow.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.contentWindow.map
wget https://github.com/cuzy-app/external-websites/tree/master/docs/examples/for-external-website-embedded-in-humhub.js
```

Add this code just before `</body>` in all pages :
```
    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="path-to-js-files/for-external-website-embedded-in-humhub.js"></script>
```

Edit `for-external-website-embedded-in-humhub.js` to customize the code to your needs.

#### Embed HumHub addons in an external website

**HumHub is embedded, external website is host (parent). HumHub addons are in an iframe.**

You must have something to auto log (and auto register if no account) the user (see below).

Allow HumHub to be embedded in an iframe by adding `frame-ancestors` in the headers: edit `proteced/config/web.php` and in the `modules` section, add:
```
        'web' => [
            'security' =>  [
                "headers" => [
                    "Content-Security-Policy" => "default-src *; connect-src  *; font-src 'self'; frame-src https://* http://* *; img-src https://* http://* * data:; object-src 'self'; script-src 'self' https://* http://* * 'unsafe-inline' 'report-sample'; style-src * https://* http://* * 'unsafe-inline'; frame-ancestors 'self' https://my-external-website.tdl;"
                ]
            ]
        ],
```
And replace `https://my-external-website.tdl` with your website URL

Upload these files on the external website server:
```
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.min.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.map
```

[See this code example for the external website to embed HumHub addons](https://github.com/cuzy-app/external-websites/tree/master/docs/examples/external-website-page-embedding-humhub-addons.php)

### HumHub embedded in an external website

It is possible to add some specific scripts (javascript) to HumHub if embedded in an iframe. In that case, in `proteced/config/common.php` add this parameter:
```
    'modules' => [
        'external-websites' => [
            'registerAssetsIfHumhubIsEmbedded' => true,
        ],
    ],
```

This will add 2 scripts:
- To add a class to the html tag to know if HumHub is in an iframe or not
- iframeResizer.contentWindow.js file enabling to the external website to resize the iframe containing HumHub (see https://github.com/davidjbradshaw/iframe-resizer)

On the external website:

Upload these files on the external website server:
```
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.min.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.js
wget https://github.com/cuzy-app/external-websites/tree/master/resources/js/iframeResizer/iframeResizer.map
```

[See this code example for the external website to embed full HumHub](https://github.com/cuzy-app/external-websites/tree/master/docs/examples/external-website-page-embedding-full-humhub.php)

### Space's content URL redirected to external website

In the space's module settings, it is possible to activate content redirection to external website (see "HumHub embedded in an external website").

Redirects only if the user arrives directly on the space URL. So it is still possible to navigate in the space if already in HumHub (PJax load).

Redirection URL for the settings: {humhubUrl} will be replaced with the HumHub's source URL.

E.g https://www.my-external-website.tdl?humhubUrl={humhubUrl} value will redirect https://wwww.my-humhub.tdl/s/space-name/xxx to https://www.my-external-website.tdl?humhubUrl=https://wwww.my-humhub.tdl/s/space-name/xxx

## Advanced features

### Auto add groups to user

If HumHub is embedded, it is possible to ask HumHub to add groups to the current user.

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
- In PAYLOAD:DATA, replace example with this one (1 and 2 are the groups ID):
```
{
 "groupsId": [1,2]
}
```
- VERIFY SIGNATURE: replace `your-512-bit-secret` with the secret key and uncheck `secret base64 encoded`
- Copy the encoded token

In the iframe `src` attribute, you must add this param to the URL:
```
token=your-encoded-token
```

[See this code example](https://github.com/cuzy-app/external-websites/tree/master/docs/examples/external-website-page-embedding-full-humhub.php)

### Hide some elements

It is possible to hide some elements.

#### If HumHub is embedded in an external website

By adding params in the URL (`showComments`, `showLikes` and `showPermalink`). [See this code example](https://github.com/cuzy-app/external-websites/tree/master/docs/examples/external-website-page-embedding-humhub-addons.php)

#### If the external website is embedded

By adding data attributes in the `<head>` tag (of the external website page):
- `data-external-comments="0"` will hide comments (in this case, the addons are shown above the external page instead of in the right panel)
- `data-external-likes="0"` will hide likes possibility
- `data-external-permalink="0"` will hide permalink

E.g.: `<head data-external-likes="0">`

### Specific behaviors

#### If multiple websites in the same space

It is possible to have several websites of the same external website in the same space. In this case, HumHub addons are shared with the websites and the HumHub addons will be related to the website having the smaller `sort_order`.

#### Archiving content

If the content related to a page is archived and all comments have been removed, only the permalink will be shown

#### Notifications (following)

For HumHub addons, each content created (if new comment about a website's page) has for creator the website creator. This creator will not follow the content by default. But all users that have chosen to receive a notification for all new content will follow this content.

## Troubleshooting

### The external website doesn't appear

If in the browser's developer console you have the message: `Failed to execute 'postMessage' on 'DOMWindow': The target origin provided ('http://my-external.website') does not match the recipient window's origin ('null').`

2 possibilities :
- The URL external website must starts with `https` (and not `http`).
- The `x-Frame-Options` value is set to `Deny` or `Sameorigin` on the external website's header
