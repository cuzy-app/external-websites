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
    <script>
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
        window.onhashchange = function() {
            sendUrlToParentIframe();
        };
        // Send new URL to parent iframe
        function sendUrlToParentIframe() {
            if ('parentIFrame' in window) {
                window.parentIFrame.sendMessage({
                  url: window.location.href,
                  title: document.getElementsByTagName("title")[0].innerText
                });
            }
        }
    </script>

    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
```


## Changelog

### Version 0.1

First release !

As the config page is not yet coded, to add a page (visiblity private) :
```
INSERT INTO `iframe_container_page` (`id`, `space_id`, `title`, `icon`, `start_url`, `target`, `sort_order`, `state`, `comments_global_state`, `visibility`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (NULL, '0', 'My Title', 'fa-graduation-cap', 'http://localhost/test/', 'SpaceMenu', '0', 'Members', 'Enabled', '0', '2020-02-13 11:11:00', '1', '2020-02-13 11:11:00', '1');
```

### Version 0.2

- Added WallEntry widget
- Added Title in `ContainerUrl` and `Url` models
- **Changed the code for the iframed website**

```
ALTER TABLE `iframe_url` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `url`;
ALTER TABLE `iframe_container_url` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `url`; 
```

## TBD

- On the wall, comments are allways Enabled
- `public $autoAddToWall = false;` doesn't work
- `public $canMove = false;` doesn't work
- Notifications : text are not correct and mail subject is not good
- Config to manage pages
- Possibility for admin to change comment state for an URL