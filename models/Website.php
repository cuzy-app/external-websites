<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use humhub\components\ActiveRecord;
use humhub\modules\comment\models\Comment;
use humhub\modules\like\models\Like;
use humhub\modules\space\models\Space;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Json;

/**
 * Websites to which we want to add addons to the pages (comments, like, files, etc.)
 *
 * @property int $id
 * @property int $space_id
 * @property string $title
 * @property string $icon Fontawesome
 * @property boolean $humhub_is_embedded
 * @property string $first_page_url
 * @property string $page_url_params_to_remove json
 * @property boolean $show_in_menu
 * @property int $sort_order
 * @property string $remove_from_url_title
 * @property boolean $hide_sidebar If Enterprise theme
 * @property null|integer $default_content_visibility Default value for the Content created ; can be humhub\modules\content\models\Content::VISIBILITY_PRIVATE or Content::VISIBILITY_PUBLIC or Content::VISIBILITY_OWNER
 * @property int $default_content_archived Default value for the Content created ; can be 0 or 1 (if 1, new comments are disabled) : humhub\modules\content\models\Content->archive(), humhub\modules\content\models\Content->unarchive()
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Page[] $pages
 * @property Space $space
 */
class Website extends ActiveRecord
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
            'page_url_params_to_remove' => Yii::t('ExternalWebsitesModule.base', 'Page URL params to ignore'),
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
            [['page_url_params_to_remove'], 'safe'],
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


    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function afterDelete()
    {
        foreach ($this->pages as $page) {
            // If this page is used by another website, move it to this one and do not remove it
            $otherWebsiteIds = $page->getOtherWebsiteIds();
            if (count($otherWebsiteIds) > 0) {
                // Get website with the smaller sort order
                $targetWebsite = self::find()
                    ->where(['id' => $otherWebsiteIds])
                    ->orderBy(['sort_order' => SORT_ASC])
                    ->one();
                if ($targetWebsite !== null) {
                    $page->website_id = $targetWebsite->id;
                    $page->setOtherWebsiteIds(array_diff($otherWebsiteIds, [$targetWebsite->id]));
                    $page->save();
                    continue;
                }
            }

            $page->delete();
        }

        // Remove this website ID from pages' other_website_ids
        foreach (Page::findWhereOtherWebsiteId($this->id)->all() as $page) {
            $page->setOtherWebsiteIds(array_diff($page->getOtherWebsiteIds(), [$this->id]));
            $page->save();
        }

        parent::afterDelete();
    }

    /**
     * @param array $params
     * @return void
     */
    public function setPageUrlParamsToRemove(array $params)
    {
        $this->page_url_params_to_remove = Json::encode($params);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (array_key_exists('page_url_params_to_remove', $changedAttributes)) {
            // Remove params to ignore
            foreach ($this->pages as $page) {
                foreach ($this->getPageUrlParamsToRemove() as $param) {
                    $page->page_url = Page::stripParamFromUrl($page->page_url, $param);
                }
                $page->save();
            }
            // Merge pages that have the same page_url (because of params removed)
            $duplicatedPages = Page::find()
                ->groupBy('page_url')
                ->having('COUNT(*) > 1')
                ->indexBy('page_url')
                ->all();
            foreach (array_keys($duplicatedPages) as $duplicatedUrl) {
                $pages = Page::find()
                    ->joinWith('website')
                    ->where(['external_websites_website_page.page_url' => $duplicatedUrl])
                    ->orderBy(['external_websites_website.sort_order' => SORT_ASC])
                    ->all();
                $firstPage = null;
                foreach ($pages as $page) {
                    // Keep only first page (that have the lowest website sort_order)
                    if ($firstPage === null) {
                        $firstPage = $page;
                        continue;
                    }
                    // Move comments to first page
                    $comments = Comment::findAll([
                        'object_model' => Page::class,
                        'object_id' => $page->id,
                    ]);
                    foreach ($comments as $comment) {
                        $comment->object_id = $firstPage->id;
                        $comment->save();
                    }
                    // Move likes to first page
                    $likes = Like::findAll([
                        'object_model' => Page::class,
                        'object_id' => $page->id,
                    ]);
                    foreach ($likes as $like) {
                        $like->object_id = $firstPage->id;
                        $like->save();
                    }
                    // Delete duplicated page
                    $page->delete();
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function getPageUrlParamsToRemove()
    {
        return (array)Json::decode($this->page_url_params_to_remove);
    }
}