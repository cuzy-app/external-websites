Module Iframe
==========================

## Description

Creates pages containing an iframed website where members can comment.

@link https://gitlab.com/funkycram/module-humhub-iframe
@license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
@author [FunkycraM](https://marc.fun)


## Description

Iframe module for Humhub.
Enables to create pages integrating iframe content.
Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).
Creates a content each time the URL in the iframe changes, and shows related comments.


## Usage

You must copy `iframeResizer.contentWindow.min.js` file (present in the `for-iframed-website` of this humhub plugin, or [download here](https://gitlab.com/funkycram/module-humhub-iframe/-/raw/master/for-iframed-website/iframeResizer.contentWindow.min.js?inline=false)) on the server hosting the website contained within your iFrame and load it adding this code just before `</body>` :
```
    <script type="text/javascript">
        // When iFrameResizer is loaded
        var iFrameResizerLoaded = false; // avoid loading twice (iFrameResizer bug)
        var iFrameResizer = {
            onReady: function(message) {
                if (!iFrameResizerLoaded) {
                    sendUrlToParentIframe();
                }
                iFrameResizerLoaded = true;
            }
        };
        // If URL changes without reloading page (ajax)
        window.addEventListener('locationchange', function(){
            sendUrlToParentIframe();
        });
        // Send new URL to parent iframe
        function sendUrlToParentIframe() {
            if ('parentIFrame' in window) {
                window.parentIFrame.sendMessage({
                  url: location.href.replace(location.hash,""),
                  title: document.getElementsByTagName("title")[0].innerText
                });
            }
        }
    </script>

    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
```

As the config page is not yet coded, to add a page (visiblity private, hide sidebar) :
```
INSERT INTO `iframe_container_page` (`id`, `space_id`, `title`, `icon`, `start_url`, `target`, `sort_order`, `comments_global_state`, `remove_from_url_title`, `content_archived`, `hide_sidebar`, `show_widget`, `visibility`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (NULL, '0', 'My Title', 'fa-graduation-cap', 'http://localhost/test/', 'SpaceMenu', '0', 'Enabled', '', '1', '1', '1', '0', '2020-02-13 11:11:00', '1', '2020-02-13 11:11:00', '1');
```



## Changelog

### Version 0.1

First release !

### Version 0.2

- Added WallEntry widget
- Added Title in `ContainerUrl` and `Url` models
- **Changed the code for the iframed website**

```
ALTER TABLE `iframe_url` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `url`;
ALTER TABLE `iframe_container_url` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `url`; 
```


### Version 0.3

- Better parameters for stream and notifications

### Version 0.4

- Added `content_archived` and `show_widget` in ContainerPage table
- Removed `state` in ContainerPage table

```
ALTER TABLE `iframe_container_page` DROP `state`;
ALTER TABLE `iframe_container_page` ADD `remove_from_url_title` VARCHAR(255) NULL DEFAULT NULL AFTER `comments_global_state`, ADD `content_archived` TINYINT(4) NOT NULL DEFAULT '0' AFTER `remove_from_url_title`, ADD `show_widget` TINYINT(4) NOT NULL DEFAULT '0' AFTER `content_archived`;
ALTER TABLE `iframe_page` DROP `state`;
ALTER TABLE `iframe_page` ADD `remove_from_url_title` VARCHAR(255) NULL DEFAULT NULL AFTER `comments_global_state`, ADD `show_widget` TINYINT(4) NOT NULL DEFAULT '0' AFTER `remove_from_url_title`;
```

### Version 0.5

- Added current page’s permalink
- Changes the navigator URL with the current page permalink

### Version 0.5.1

- Show comments in a right panel

### Version 0.5.2

- Added compatibility to Humhub 1.4

### Version 0.6

- Show comments in a right panel only if screen is wider than 1700 px (see `module.css`)
- Bug correction : comments where shown in plain text
- Comment form is shown in the page (no modal box anymore)
- Option to hide sidebar menu (enterprise theme, `#sidebar-wrapper` element)

```
ALTER TABLE `iframe_page` ADD `hide_sidebar` TINYINT(4) NOT NULL DEFAULT '0' AFTER `remove_from_url_title`; 
ALTER TABLE `iframe_container_page` ADD `hide_sidebar` TINYINT(4) NOT NULL DEFAULT '0' AFTER `content_archived`; 
```

### Version 0.6.1

- Hide sidebar was allways true, now 0 value don't hide sidebar

### Version 0.6.2

- Show number of comments and better presentation of permalink, link and comments


## TBD

- On the wall, comments are allways Enabled
- Config to manage pages
- Possibility for admin to change comment state for an URL