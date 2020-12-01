Changelog
=========

V0.20 (November, 25, 2020)
--------------------
- Enh: Added websites managment interface (add, edit, delete)
- Enh: It is now possible to embed Humhub addons in the external website (Humhub is in guest mode)
- Enh: Guest mode: Added auto login with SSO, auto add to space and related group members
- Enh: If the content related to a page is archived and all comments have been removed, only the permalink will be shown
- Enh: Wall entry updated for Humhub 1.7
- Enh: Documentation on module usage in `docs/README.md`
- Chg: Module renamed (iframe -> external-websites)
- Chg: Script in the external website if guest (integrated in iframe): `url` is now `pageUrl` and `title` is now `pageTitle`
- Chg: Changed name of the tables, files, models, controller and view
- Chg: Changed `FirstCommentForm` widget to new Hummhub 1.7 specifications


V0.15 (November, 23, 2020)
--------------------
- Enh: Moving files to recommanded Humhub module structure: https://docs.humhub.org/docs/develop/modules/



Previous changelogs:
--------------------

### Version 0.1

First release !

### Version 0.2

- Added WallEntry widget
- Added Title in `Page` and `Url` models
- **Changed the code for the iframed website**


### Version 0.3

- Better parameters for stream and notifications

### Version 0.4

- Added `content_archived` and `show_widget` in Website table
- Removed `state` in Website table

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

### Version 0.6.1

- Hide sidebar was allways true, now 0 value don't hide sidebar

### Version 0.6.2

- Show number of comments and better presentation of permalink, link and comments

### Version 0.7

- Changed licence to https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
- Added filters in wall
- Added space configuration to hide content by default with the filters
- Updated usage explainations in this README.md file

### Version 0.8

- Small bug correction in CSS (for large screen)
- Removed `comments_global_state` and `comments_state` as with the new Humhub 1.4.4 an archived content cannot be commented
- Added `archived` param in `iframe_container_page`
- Deleted `iframe_page` and `iframe_url` tables and models as unused

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

- Fix: `Page->getSearchAttributes` was creating bugs with module search
- Enh: For all users that receive notifications for new content, make them follow the content to sent notifications if new comments, as this module doesn't send notification for each new content to avoid huge amount of notifications (a new content is created for each iframed page visited !)

### Version 0.11

- Enh: Dashboard : Hide content related to Page with `hide_in_stream` === true and content with no comment

### Version 0.12

- When insert in table `container_url`, field `created_by` of the insert and the related content takes the value of the related `container_page`

### Version 0.13

- No change, only some code rewrited better

### Version 0.14

- Enh: content not created before commenting
