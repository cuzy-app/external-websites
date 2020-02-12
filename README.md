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
<script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
```


## Changelog

### Version 1.0

First release !



## TBD
