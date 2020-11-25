# Module Iframe


## Overview

Creates pages containing an iframed website where members can comment.
Creates a content each time the URL in the iframe changes, and shows related comments.

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Usage

### Embed external site in iframe

Copy the files in the folder [for-iframed-website](https://gitlab.com/funkycram/humhub-modules-iframe/-/tree/master/docs/install/for-iframed-website) on the server hosting the website contained within your iFrame. Or, download them with this command line :
```
wget https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/install/for-iframed-website/iframeResizer.contentWindow.min.js
wget https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/install/for-iframed-website/iframeResizer.contentWindow.js
wget https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/install/for-iframed-website/iframeResizer.contentWindow.map
wget https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/install/for-iframed-website/humhubIframeModule.js
```

And load them adding this code just before `</body>` :
```
    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="path-to-js-files/humhubIframeModule.js"></script>
```

For CODIMD in a docker, [see this documentation](https://gitlab.com/funkycram/doc/-/wikis/CodiMd#add-humhub-iframe-module-script-using-dockerfile)

As the config page is not yet coded, to add a page (hide sidebar, visiblity private, not archived), use this MySQL command (title must be unique) :
```
INSERT INTO `iframe_container_page` (`space_id`, `title`, `icon`, `start_url`, `target`, `sort_order`, `remove_from_url_title`, `hide_sidebar`, `show_widget`, `visibility`, `archived`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES ('0', 'My Title', 'fa-graduation-cap', 'http://localhost/test/', 'SpaceMenu', '0', '', '1', '1', '0', '0', '2020-02-13 11:11:00', '1', '2020-02-13 11:11:00', '1');
```

See `models/ContainerPage.php` -> `attributeLabels()` for more infos


### Embed Humhub comments in an external site

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
                    "Content-Security-Policy" => "default-src *; connect-src  *; font-src 'self'; frame-src https://* http://* *; img-src https://* http://* * data:; object-src 'self'; script-src 'self' https://* http://* * 'unsafe-inline' 'report-sample'; style-src * https://* http://* * 'unsafe-inline'; frame-ancestors 'self' https://my-external-website.com;"
                ]
            ]
        ],
```
And replace `https://my-external-website.com` with your website URL
If doesn't work, replace `"X-Frame-Options" => "sameorigin",` with `"X-Frame-Options" => "",`


## Special features

It is possible to have several instances (container page) of the same iframed website in the same space : comments and "like" are shared between the instances.

If the same URL is shared by several instances in the same space, the URL will be related to the container page having the smaller `sort_order`.