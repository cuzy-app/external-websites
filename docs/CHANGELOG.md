Changelog
=========

Unreleased
--------------------
- Enh: Add GitHub HumHub PHP workflows (tests & CS fixer)

0.43 (June 27, 2024)
---------------------
- Fix: Sometimes HumHub addons doesn't loads
- Chg: Repository URL from https://github.com/cuzy-app/humhub-modules-external-websites to https://github.com/cuzy-app/external-websites
- Chg: The minimum HumHub version is now 1.16

0.42 (June 23, 2023)
---------------------
- Fix: Added Html::nonce() to script tags for HumHub 1.15 (https://github.com/humhub/documentation/pull/84/files)

0.41 (May 21, 2023)
---------------------
- Fix: First comment buttons for HumHub 1.14

0.40 (May 7, 2023)
---------------------
- Fix: Don't show external website pages pending deletion (for the new soft deletion of HumHub 1.14)

0.39 (May 1, 2023)
--------------------
- Enh: Removed HumHub auto-login with Keycloak (as removed in Keycloak module v1.3.0)
- Fix #1: Links to module documentation not work.
- Fix: Hard delete records on disable module (for HumHub 1.14)
- Chg: Minimum HumHub version is now 1.14

0.38 (March 27, 2023)
--------------------
- Enh: Added possibility to show the embedded website in fullscreen.

0.37 (January 24, 2023)
--------------------
- Enh: Hide left navigation menu on Clean Theme

0.36 (November 20, 2022)
--------------------
- Enh: Compatibility with HumHub 1.13

0.35 (March 3, 2022)
--------------------
- Enh: Possibility to ignore some params in the external website URL to link multiple URLs to a same content if only theses params are different

0.34 (March 3, 2022)
--------------------
- Enh: if the same URL is used by different websites and a website is deleted, the page is assigned to the other website

0.33 (November 13, 2021)
--------------------
- Fix: small bugs in website editor (visibiliy: default and website owner)

0.32 (November 8, 2021)
--------------------
- Chg: Websites owner selector moved to add and edit modal box

0.31 (October 22, 2021)
--------------------
- Enh: Websites owner selector

0.30 (September 7, 2021)
--------------------
- Fix: if a user cannot view the page's comments or likes (e.g. the page's content is private and the user is not a member of the space), do not show them to him.

0.29 (August 17, 2021)
--------------------
- Enh: If HumHub is embedded, possibility to hide some elements

0.28 (June 8, 2021)
--------------------
- Enh: The icon selector widget has been replaced with the HumHub's native one (requires a database migration)

0.27 (May, 31, 2021)
--------------------
- Enh: To show a website embedded in HumHub, it is now possible to specify in the URL the title of the website instead of the ID (e.g. `/s/space-name/external-websites/website?title=title%20of%20the%20webiste&pageId=1`)

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
- Fix: When a website is embeded in HumHub, in some cases (page refresh mainly) comments could not be loaded (`loadIFrameResizer()` could called before JS assets file was loaded)

0.22 (March, 2, 2021)
--------------------
- Enh: Added possibility to hide some elements (if the external website is embedded) by adding data attributes in the `<head>` tag. Elements that can be hidden are comments, likes or permalink

0.21.1 (January, 21, 2021)
--------------------
- Enh: Changed height calculation method with iframeResizer when HumHub addons are embedded in an external website

0.21 (December, 17, 2020)
--------------------
- Enh: When HumHub space is embedded, added settings to prevent clicking on links making leave the curent space
- Enh: When HumHub addons are embedded, prevent clicking on links making leave the curent space

0.20 (December, 15, 2020)
--------------------
- Chg: Module renamed (iframe -> external-websites)
- Enh: Added websites management interface (add, edit, delete)
  Enh: Added space settings to redirect contents to an external website
- Enh: It is now possible to embed HumHub addons in the external website (HumHub is embedded)
  Enh: If full HumHub is embeded, possibility to add iframeResizer plugin so that the external website can auto resize iframe window
- Enh: Guest mode: Added auto login with SSO
- Enh: If the content related to a page is archived and all comments have been removed, only the permalink will be shown
- Enh: Wall entry updated for HumHub 1.7
- Enh: Documentation on module usage in `docs/README.md`
- Enh: Possibility to auto add groups to the current user via an JWT token
- Chg: Script in the external website if embedded HumHub: `url` is now `pageUrl` and `title` is now `pageTitle`
- Chg: Changed name of the tables, files, models, controller and view
- Chg: Changed `FirstCommentForm` widget to new Hummhub 1.7 specifications

0.15 (November, 23, 2020)
--------------------
- Enh: Moving files to recommanded HumHub module structure: https://docs.humhub.org/docs/develop/modules/

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

- Added compatibility to HumHub 1.4

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

- Changed licence to https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
- Added filters in wall
- Added space configuration to hide content by default with the filters
- Updated usage explainations in this README.md file

### Version 0.8

- Small bug correction in CSS (for large screen)
- Removed `comments_global_state` and `comments_state` as with the new HumHub 1.4.4 an archived content cannot be commented
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

- Update for HumHub 1.5 - changed minimum compatibility to HumHub 1.5

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
