Module Iframe
==========================

## Description

Creates pages containing an iframed website where members can comment.
Creates a content each time the URL in the iframe changes, and shows related comments.

@link https://gitlab.com/funkycram/module-humhub-iframe
@license https://www.humhub.com/licences
@author [FunkycraM](https://marc.fun)

Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).


## Usage

Copy `for-iframed-website/iframeResizer.contentWindow.min.js` and `for-iframed-website/humhubIframeModule.js` files on the server hosting the website contained within your iFrame. Or, download them with this command line :
```
wget https://gitlab.com/funkycram/module-humhub-iframe/-/raw/master/for-iframed-website/iframeResizer.contentWindow.min.js
wget https://gitlab.com/funkycram/module-humhub-iframe/-/raw/master/for-iframed-website/humhubIframeModule.js
```

And load them adding this code just before `</body>` :
```
    <script type="text/javascript" src="path-to-js-files/humhubIframeModule.js"></script>
    <script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
```

As the config page is not yet coded, to add a page (visiblity private, hide sidebar), use this MySQL command :
```
INSERT INTO `iframe_container_page` (`id`, `space_id`, `title`, `icon`, `start_url`, `target`, `sort_order`, `default_comments_state`, `remove_from_url_title`, `default_hide_in_stream`, `hide_sidebar`, `show_widget`, `visibility`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (NULL, '0', 'My Title', 'fa-graduation-cap', 'http://localhost/test/', 'SpaceMenu', '0', 'Enabled', '', '1', '1', '1', '0', '2020-02-13 11:11:00', '1', '2020-02-13 11:11:00', '1');
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

### Version 0.7

- Changed licence to https://www.humhub.com/licences
- Added filters in wall
- Added space configuration to hide content by default with the filters
- Updated usage explainations in this README.md file

```
ALTER TABLE `iframe_container_page` CHANGE `content_archived` `default_hide_in_stream` TINYINT NOT NULL DEFAULT '0'; 
ALTER TABLE `iframe_container_page` CHANGE `comments_global_state` `default_comments_state` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;
ALTER TABLE `iframe_container_url` ADD `hide_in_stream` TINYINT(4) NOT NULL DEFAULT '0' AFTER `container_page_id`; 
```


## TBD

- On the wall, comments are allways Enabled
- Config to manage pages
- Possibility for admin to change comment state for an URL