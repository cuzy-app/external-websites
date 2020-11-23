Changelog
=========

V0.15 (November, 23, 2020)
--------------------
- Enh: Moving files to recommanded Humhub module structure: https://docs.humhub.org/docs/develop/modules/



Previous changelogs:
--------------------

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

- Changed licence to https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
- Added filters in wall
- Added space configuration to hide content by default with the filters
- Updated usage explainations in this README.md file

```
ALTER TABLE `iframe_container_page` CHANGE `content_archived` `default_hide_in_stream` TINYINT NOT NULL DEFAULT '0'; 
ALTER TABLE `iframe_container_page` CHANGE `comments_global_state` `default_comments_state` VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE `iframe_container_url` ADD `hide_in_stream` TINYINT(4) NOT NULL DEFAULT '0' AFTER `container_page_id`; 
```

### Version 0.8

- Small bug correction in CSS (for large screen)
- Removed `comments_global_state` and `comments_state` as with the new Humhub 1.4.4 an archived content cannot be commented
- Added `archived` param in `iframe_container_page`
- Deleted `iframe_page` and `iframe_url` tables and models as unused

```
DROP TABLE `iframe_page`, `iframe_url`;
ALTER TABLE `iframe_container_url` DROP `comments_state`;
ALTER TABLE `iframe_container_page` DROP `default_comments_state`;
ALTER TABLE `iframe_container_page` ADD `archived` TINYINT(4) NOT NULL DEFAULT '0' AFTER `visibility`; 
ALTER TABLE `iframe_container_page` CHANGE `visibility` `visibility` TINYINT(4) NULL DEFAULT '0'; 
```

### Version 0.8.1

- If the URL specify a specific content (`?contentId=xxx`), don't apply filter to hide it

### Version 0.8.2

- Fix: Added missing javascript map files for iframe-resizer

### Version 0.8.3

- Iframe resiser updated to version 4.2.10

### Version 0.8.4

- Bug resolution in `migrations/xxx_initial.php` -> `down` function

### Version 0.8.5

- Update for Humhub 1.5 - changed minimum compatibility to Humhub 1.5

### Version 0.8.6

- Fix in `PageController.php`

### Version 0.8.7

- Resize iframe if enterprise theme sidebar menu is toggled

### Version 0.8.8

- Fix : `remove_from_url_title` is now working after page updated

### Version 0.8.9

- Fix : if same title in different spaces, wrong container could be fetched

### Version 0.8.10

- Hide content on stream without any comment

### Version 0.9

- Enables to have several instances (container page) of the same iframed website : comments and "like" are shared between the instances
- If the same URL is shared by several instances, the URL will be related to the container page having the smaller `sort_order`

### Version 0.9.1

- Fix in filter

### Version 0.9.2

- Scroll to top when URL in iframe changes

### Version 0.10

- Fix: `ContainerUrl->getSearchAttributes` was creating bugs with module search
- Enh: For all users that receive notifications for new content, make them follow the content to sent notifications if new comments, as this module doesn't send notification for each new content to avoid huge amount of notifications (a new content is created for each iframed page visited !)

### Version 0.11

- Enh: Dashboard : Hide content related to ContainerUrl with `hide_in_stream` === true and content with no comment

### Version 0.12

- When insert in table `container_url`, field `created_by` of the insert and the related content takes the value of the related `container_page`

### Version 0.13

- No change, only some code rewrited better

### Version 0.14

- Enh: content not created before commenting
