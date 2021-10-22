<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use Yii;
use humhub\modules\space\models\Space;

/**
 * Websites to which we want to add addons to the pages (comments, like, files, etc.)
 * 
 * @property integer $space_id
 * @property string $title
 * @property string $icon Fontawesome
 * @property boolean $humhub_is_embedded
 * @property string $first_page_url
 * @property boolean $show_in_menu
 * @property integer $sort_order 
 * @property string $remove_from_url_title
 * @property boolean $hide_sidebar If Enterprise theme
 * @property integer $default_content_visibility Default value for the Content created ; can be humhub\modules\content\models\Content::VISIBILITY_PRIVATE or Content::VISIBILITY_PUBLIC or Content::VISIBILITY_OWNER
 * @property integer $default_content_archived Default value for the Content created ; can be 0 or 1 (if 1, new comments are disabled) : humhub\modules\content\models\Content->archive(), humhub\modules\content\models\Content->unarchive()
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Page[] $pages
 * @property Space $space
 */
class Website extends \humhub\components\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'external_websites_website';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'space_id' => Yii::t('ExternalWebsitesModule.base', 'Space ID'),
            'title' => Yii::t('ExternalWebsitesModule.base', 'Title'),
            'icon' => Yii::t('ExternalWebsitesModule.base', 'Icon'),
            'humhub_is_embedded' => Yii::t('ExternalWebsitesModule.base', 'Humhub is embedded'),
            'first_page_url' => Yii::t('ExternalWebsitesModule.base', 'Website first page URL'),
            'show_in_menu' => Yii::t('ExternalWebsitesModule.base', 'Show in space menu'),
            'sort_order' => Yii::t('ExternalWebsitesModule.base', 'Sort order'),
            'remove_from_url_title' => Yii::t('ExternalWebsitesModule.base', 'Text to remove from URL title'),
            'hide_sidebar' => Yii::t('ExternalWebsitesModule.base', 'Hide sidebar'),
            'default_content_visibility' => Yii::t('ExternalWebsitesModule.base', 'Content visibility default value'),
            'default_content_archived' => Yii::t('ExternalWebsitesModule.base', 'Archive contents when they are created'),
            'created_at' => 'Created at',
            'created_by' => Yii::t('ExternalWebsitesModule.base', 'Owner'),
            'updated_at' => 'Updated at',
            'updated_by' => 'Updated by',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
           [['space_id', 'first_page_url'], 'required'],
           [['title', 'icon', 'first_page_url', 'remove_from_url_title'], 'string'],
           [['space_id', 'sort_order', 'default_content_visibility', 'default_content_archived'], 'integer'],
           [['humhub_is_embedded', 'show_in_menu', 'hide_sidebar'], 'boolean'],
       ];
    }


    public function getPages()
    {
        return $this
            ->hasMany(Page::class, ['website_id' => 'id']);
    }

    public function getSpace()
    {
        return $this
            ->hasOne(Space::class, ['id' => 'space_id']);
    }

    public function getUrl()
    {
        return $this->space->createUrl('/external-websites/website', ['id' => $this->id]);
    }


    public function afterDelete()
    {
        foreach (Page::find()->contentContainer($this->space)->all() as $page) {
            $page->delete();
        }

        parent::afterDelete();
    }
}