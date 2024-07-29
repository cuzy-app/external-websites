<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\externalWebsites\widgets\WallEntry;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Json;


/**
 * Contents corresponding to the pages of the website to which we want to add addons (comments, like, files, etc.)
 * For each page of the website, when a first comment is posted, a content is created
 * The relation is done with the URL of the page
 *
 * @property int $id
 * @property int $title
 * @property string $page_url
 * @property int $website_id
 * @property string $other_website_ids json
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Website $website
 */
class Page extends ContentActiveRecord
{
    public $moduleId = 'external-websites';

    /**
     * @inheritdoc
     */
    public $wallEntryClass = WallEntry::class;

    public $streamChannel = 'default';

    public $canMove = false;

    /**
     * @var boolean should the originator automatically follows this content when saved.
     */
    public $autoFollow = true;

    /**
     * If set to true this flag will prevent default ContentCreated Notifications and Activities.
     * This can be used e.g. for sub content entries, whose creation is not worth mentioning.
     * @var bool
     */
    public $silentContentCreation = true;

    /**
     * @param int $id
     * @return ActiveQueryContent
     */
    public static function findWhereOtherWebsiteId(int $id)
    {
        $field = static::tableName() . '.' . 'other_website_ids';
        return static::find()
            ->readable()
            ->andWhere([
                'or',
                [$field => '[' . $id . ']'],
                ['like', $field, '[' . $id . ",%", false],
                ['like', $field, "%," . $id . ",%", false],
                ['like', $field, "%," . $id . ']', false],
            ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'external_websites_website_page';
    }

    /**
     * @param string $url
     * @param string $param
     * @return string
     */
    public static function stripParamFromUrl(string $url, string $param)
    {
        $base_url = strtok($url, '?');
        $parsed_url = parse_url($url);
        if (empty($parsed_url['query'])) {
            return $url;
        }
        $query = $parsed_url['query']; // Get the query string
        parse_str($query, $parameters); // Convert Parameters into array
        unset($parameters[$param]);
        $new_query = http_build_query($parameters);
        return $base_url . ($new_query ? '?' . $new_query : '');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('ExternalWebsitesModule.base', 'Title'),
            'page_url' => Yii::t('ExternalWebsitesModule.base', 'Page URL'),
            'website_id' => Yii::t('ExternalWebsitesModule.base', 'Website ID'),
            'other_website_ids' => Yii::t('ExternalWebsitesModule.base', 'Other website IDs'),
            'created_at' => 'Created at',
            'created_by' => 'Created by',
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
            [['website_id', 'page_url'], 'required'],
            [['title', 'page_url'], 'string'],
            [['website_id'], 'integer'],
            [['other_website_ids'], 'safe'],
        ];
    }

    public function getWebsite()
    {
        return $this
            ->hasOne(Website::class, ['id' => 'website_id']);
    }

    public function getContentName()
    {
        if (!empty($this->website->title)) {
            return $this->website->title;
        }
        return Yii::t('ExternalWebsitesModule.base', 'Page');
    }

    public function getContentDescription()
    {
        return $this->title;
    }

    public function getUrl()
    {
        // If HumHub is host
        if (!$this->website->humhub_is_embedded) {
            return $this->website->space->createUrl('/external-websites/website', [
                'id' => $this->website->id,
                'pageId' => $this->id,
            ]);
        }

        // If HumHub is embedded
        return $this->page_url;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'message' => $this->website->title,
            // url comment because make solr crash
            // 'page_url' => $space->createUrl('/external-websites/page?title='.urlencode($this->website->title).'&pageId='.$this->id),
            'user' => $this->getPostAuthorName()
        ];
    }

    public function getIcon()
    {
        if ($this->website && $this->website->icon) {
            return 'fa-' . $this->website->icon; // the `fa-` is required for the stream filter form
        }
        return 'fa-desktop'; // the `fa-` is required for the stream filter form
    }

    /**
     * @return array
     */
    public function getOtherWebsiteIds()
    {
        $ids = Json::decode($this->other_website_ids);
        // Cast IDs to integer
        if (is_array($ids)) {
            return array_map(static function ($id) {
                return (int)$id;
            }, $ids);
        }
        return [];
    }

    /**
     * @param array $ids
     * @return void
     */
    public function setOtherWebsiteIds(array $ids)
    {
        $this->other_website_ids = Json::encode($ids);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            // Set created_by to external_websites_website creator
            $this->created_by = $this->website->created_by;
            $this->content->created_by = $this->created_by;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $obj = $this->content->getModel();

            // Turn off notifications for creator
            $obj->follow($this->content->created_by, false);

            // For all users that receive notifications for new content, make them follow the content to sent notifications if new comments, as this module doesn't send notification for each new content ($this->silentContentCreation value is true)
            $currentSpace = $this->content->container;
            foreach ($currentSpace->memberships as $membership) {
                if ($membership->send_notifications) {
                    $obj->follow($membership->user_id, true);
                }
            }
        }
    }

    /**
     * @return string
     */
    private function getPostAuthorName()
    {
        $user = User::findOne(['id' => $this->created_by]);

        if ($user !== null && $user->isActive()) {
            return $user->getDisplayName();
        }

        return '';
    }
}
