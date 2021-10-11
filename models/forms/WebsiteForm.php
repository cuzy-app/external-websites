<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\forms;

use Yii;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\content\models\Content;
use yii\base\Model;


class WebsiteForm extends Model
{
    public $id;
    public $space_id;
    public $title;
    public $icon = 'desktop';
    public $humhub_is_embedded = false;
    public $first_page_url;
    public $show_in_menu = 1;
    public $sort_order = 100;
    public $remove_from_url_title = '';
    public $hide_sidebar = 0;
    public $default_content_visibility = null;
    public $default_content_archived = 0;


    public function init()
    {
        // If editing existing Event
        if (
            $this->id !== null
            && ($website = Website::findOne($this->id)) !== null
        ) {
            $this->title = $website->title;
            $this->icon = $website->icon;
            $this->humhub_is_embedded = $website->humhub_is_embedded;
            $this->first_page_url = $website->first_page_url;
            $this->show_in_menu = $website->show_in_menu;
            $this->sort_order = $website->sort_order;
            $this->remove_from_url_title = $website->remove_from_url_title;
            $this->hide_sidebar = $website->hide_sidebar;
            $this->default_content_visibility = $website->default_content_visibility;
            $this->default_content_archived = $website->default_content_archived;
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
           [['title', 'icon', 'first_page_url', 'humhub_is_embedded'], 'required'],
           [['title', 'icon', 'first_page_url', 'remove_from_url_title'], 'string'],
           [['sort_order', 'default_content_visibility', 'default_content_archived'], 'integer'],
           [['humhub_is_embedded', 'show_in_menu', 'hide_sidebar'], 'boolean'],
       ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return (new Website())->attributeLabels();
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'humhub_is_embedded' => Yii::t('ExternalWebsitesModule.base', 'Humhub can be: <br>- Host: external website is embedded and embedded in an iframe<br>- Embedded: external website is host, Humhub addons (comments, like, files, etc.) are embedded in an iframe.<br>See README.md for more informations and usage.'),
            'remove_from_url_title' => Yii::t('ExternalWebsitesModule.base', 'The name of the Humhub content associated with each page of the external website corresponds to the page title (HTML title tag). It is possible to delete part of the text of this title.'),
            'hide_sidebar' => Yii::t('ExternalWebsitesModule.base', 'If your theme has a sidebar whose tag id is "wrapper" (e.g. Enterprise theme)'),

        ];
    }

    /**
     * Saves the current model values
     *
     * @return boolean success
     */
    public function save()
    {
        // Add
        if ($this->id === null) {
            $website = new Website;
            $website->space_id = $this->space_id;
        }
        // Update
        else {
            $website = Website::findOne($this->id);
        }

        // Save values
        $website->title = $this->title;
        $website->icon = $this->icon;
        $website->humhub_is_embedded = $this->humhub_is_embedded;
        $website->first_page_url = $this->first_page_url;
        $website->show_in_menu = $this->show_in_menu;
        $website->sort_order = $this->sort_order;
        $website->remove_from_url_title = $this->remove_from_url_title;
        $website->hide_sidebar = $this->hide_sidebar;
        $website->default_content_visibility = $this->default_content_visibility;
        $website->default_content_archived = $this->default_content_archived;
        return $website->save();
    }


    public function getYesNoList()
    {
        return [
            1 => Yii::t('ExternalWebsitesModule.base', 'Yes'),
            0 => Yii::t('ExternalWebsitesModule.base', 'No'),
        ];
    }


    public function getContentVisibilityList()
    {
        return [
            null => Yii::t('ExternalWebsitesModule.base', 'Space\'s default content visibility'),
            Content::VISIBILITY_PRIVATE => Yii::t('ExternalWebsitesModule.base', 'Private'),
            Content::VISIBILITY_PUBLIC => Yii::t('ExternalWebsitesModule.base', 'Public'),
            Content::VISIBILITY_OWNER => Yii::t('ExternalWebsitesModule.base', 'Owner'),
        ];
    }
}