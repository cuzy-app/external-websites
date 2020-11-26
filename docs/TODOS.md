TODOS 
=====

- Integrate iframeResizer.contentWindow.min.js to enable external website as host to resize automatically the iframe
- New wall stream view for Humhub 1.7
- Translations
- Config to manage pages (1)
- Possibility for admin to change comment state for an URL
- Cf `TBD`


(1)
As the config page is not yet coded, to add a page (hide sidebar, visiblity private, not archived), use this MySQL command (title must be unique) :
```
INSERT INTO `external_website` (`space_id`, `title`, `icon`, `first_page_url`, `show_in_menu`, `sort_order`, `remove_from_url_title`, `hide_sidebar`, `default_content_visibility`, `default_content_archived`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES ('0', 'My Title', 'fa-graduation-cap', 'http://localhost/test/', 1, 0, '', 1, 0, 0, '2020-02-13 11:11:00', '1', '2020-02-13 11:11:00', '1');
```

See `models/Website.php` -> `attributeLabels()` for more infos
