<?php

/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\forms;

use humhub\modules\content\models\Content;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Model;

class WebsiteForm extends Model
{
    public $id;
    public $space_id;
    public $title;
    public $icon = 'desktop';
    public $humhub_is_embedded = false;
    public $page_url_params_to_remove;
    public $first_page_url;
    public $show_in_menu = true;
    public $sort_order = 100;
    public $remove_from_url_title = '';
    public $layout = Website::LAYOUT_DEFAULT;
    /** @var null|int */
    public $default_content_visibility;
    public $default_content_archived = false;
    public $created_by; // TODO: replace with $ownerGuid (see example in personal-data/models/ModuleSettings)


    public function init()
    {
        // If editing existing Event
        if (
            $this->id !== null
            && ($website = Website::findOne($this->id)) !== null
        ) {
            $this->title = $website->title;
            $this->icon = $website->icon;
            $this->humhub_is_embedded = (bool)$website->humhub_is_embedded;
            $this->first_page_url = $website->first_page_url;
            $this->page_url_params_to_remove = implode(',', $website->getPageUrlParamsToRemove());
            $this->show_in_menu = (bool)$website->show_in_menu;
            $this->sort_order = (int)$website->sort_order;
            $this->remove_from_url_title = $website->remove_from_url_title;
            $this->layout = $website->layout;
            $this->default_content_visibility = $website->default_content_visibility; // Do not add (int) as value can be null
            $this->default_content_archived = (bool)$website->default_content_archived;
            $this->created_by = $website->created_by;
        } else {
            $this->created_by = Yii::$app->user->id;
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
            [['title', 'icon', 'first_page_url', 'remove_from_url_title', 'layout'], 'string'],
            [['sort_order', 'default_content_visibility', 'default_content_archived'], 'integer'],
            [['humhub_is_embedded', 'show_in_menu'], 'boolean'],
            [['created_by', 'page_url_params_to_remove'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributesLabels = (new Website())->attributeLabels();
        $attributesLabels['page_url_params_to_remove'] .= ' (' . Yii::t('ExternalWebsitesModule.base', 'separated by commas') . ')';
        return $attributesLabels;
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'humhub_is_embedded' => Yii::t('ExternalWebsitesModule.base', 'Humhub can be: <br>- Host: external website is embedded and embedded in an iframe<br>- Embedded: external website is host, Humhub addons (comments, like, files, etc.) are embedded in an iframe.<br>See README.md for more informations and usage.'),
            'page_url_params_to_remove' => Yii::t('ExternalWebsitesModule.base', 'Allows to ignore some params in the external website URL to link multiple URLs to a same content if only theses params are different.'),
            'remove_from_url_title' => Yii::t('ExternalWebsitesModule.base', 'The name of the Humhub content associated with each page of the external website corresponds to the page title (HTML title tag). It is possible to delete part of the text of this title.'),
            'created_by' => Yii::t('ExternalWebsitesModule.base', 'Website owner (related content for comments will be created with this user)'),
        ];
    }

    /**
     * Saves the current model values
     *
     * @return bool success
     */
    public function save()
    {
        // If add
        if ($this->id === null) {
            $website = new Website();
            $website->space_id = $this->space_id;
        } // If update
        else {
            $website = Website::findOne($this->id);
        }

        // Trim URL params to remove and explode to array
        $pageUrlParamsToRemove = [];
        foreach ((array)explode(',', (string)$this->page_url_params_to_remove) as $param) {
            $pageUrlParamsToRemove[] = trim((string)$param);
        }

        // Save values
        $website->title = $this->title;
        $website->icon = $this->icon;
        $website->humhub_is_embedded = $this->humhub_is_embedded;
        $website->first_page_url = $this->first_page_url;
        $website->setPageUrlParamsToRemove($pageUrlParamsToRemove);
        $website->show_in_menu = $this->show_in_menu;
        $website->sort_order = $this->sort_order;
        $website->remove_from_url_title = $this->remove_from_url_title;
        $website->layout = $this->layout;
        $website->default_content_visibility = $this->default_content_visibility;
        $website->default_content_archived = $this->default_content_archived;
        $userGuid = is_array($this->created_by) ? reset($this->created_by) : null;
        $website->created_by = ($owner = User::findOne(['guid' => $userGuid])) ? $owner->id : ($website->created_by ?? Yii::$app->user->id);
        $result = $website->save();

        // Update pages owner
        if ($result) {
            foreach ($website->pages as $page) {
                if ($page->created_by !== $website->created_by) {
                    $page->created_by = $website->created_by;
                    $page->save();
                }
                $content = $page->content;
                if ($content->created_by !== $website->created_by) {
                    $content->created_by = $website->created_by;
                    $content->save();
                }
            }
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getLayoutList()
    {
        return [
            null => Yii::t('ExternalWebsitesModule.base', 'Space\'s default content visibility'),
            Website::LAYOUT_DEFAULT => Yii::t('ExternalWebsitesModule.base', 'The space\'s default one'),
            Website::LAYOUT_MENU_COLLAPSED => Yii::t('ExternalWebsitesModule.base', 'Menu collapsed'),
            Website::LAYOUT_FULL_SCREEN => Yii::t('ExternalWebsitesModule.base', 'Full screen'),
        ];
    }


    /**
     * @return array
     */
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
