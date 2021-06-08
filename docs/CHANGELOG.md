Changelog
=========

0.28 (June 8, 2021)
--------------------
- Enh: The icon selector widget has been replaced with the Humhub's native one (requires a database migration)

0.27 (May, 31, 2021)
--------------------
- Enh: To show a website embedded in Humhub, it is now possible to specify in the URL the title of the website instead of the ID (e.g. `/s/space-name/external-websites/website?title=title%20of%20the%20webiste&pageId=1`)

0.26 (April, 26, 2021)
--------------------
- Enh: Prevent pages author from following the new pages

0.25 (April, 20, 2021)
--------------------
- Fix: Events could make console commands crash

0.24 (March, 18, 2021)
--------------------
- Fix: Various fix for websites embeded


0.23 (March, 11, 2021)
--------------------
- Fix: When a website is embeded in Humhub, in some cases (page refresh mainly) comments could not be loaded (`loadIFrameResizer()` could called before JS assets file was loaded)


0.22 (March, 2, 2021)
--------------------
- Enh: Added possibility to hide some elements (if the external website is embedded) by adding data attributes in the `<head>` tag. Elements that can be hidden are comments, likes or permalink


0.21.1 (January, 21, 2021)
--------------------
- Enh: Changed height calculation method with iframeResizer when Humhub addons are embedded in an external website


0.21 (December, 17, 2020)
--------------------
- Enh: When Humhub space is embedded, added settings to prevent clicking on links making leave the curent space
- Enh: When Humhub addons are embedded, prevent clicking on links making leave the curent space


0.20 (December, 15, 2020)
--------------------
- Chg: Module renamed (iframe -> external-websites)
- Enh: Added websites management interface (add, edit, delete)
  Enh: Added space settings to redirect contents to an external website
- Enh: It is now possible to embed Humhub addons in the external website (Humhub is embedded)
  Enh: If full Humhub is embeded, possibility to add iframeResizer plugin so that the external website can auto resize iframe window
- Enh: Guest mode: Added auto login with SSO
- Enh: If the content related to a page is archived and all comments have been removed, only the permalink will be shown
- Enh: Wall entry updated for Humhub 1.7
- Enh: Documentation on module usage in `docs/README.md`
- Enh: Possibility to auto add groups to the current user via an JWT token
- Chg: Script in the external website if embedded Humhub: `url` is now `pageUrl` and `title` is now `pageTitle`
- Chg: Changed name of the tables, files, models, controller and view
- Chg: Changed `FirstCommentForm` widget to new Hummhub 1.7 specifications


0.15 (November, 23, 2020)
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
