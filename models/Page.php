<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\user\models\User;
use humhub\modules\externalWebsites\widgets\WallEntry;


/**
 * Contents corresponding to the pages of the website to which we want to add addons (comments, like, files, etc.)
 * For each page of the website, when a first comment is posted, a content is created
 * The relation is done with the URL of the page
 * 
 * @property integer $id
 * @property integer $title
 * @property string $page_url
 * @property integer $website_id
 */

class Page extends ContentActiveRecord implements Searchable
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'external_websites_website_page';
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
        // If Humhub is host
        if ($this->website->humhub_is_host) {
            return $this->website->space->createUrl('/external-websites/website', [
                'id' => $this->website->id,
                'pageId' => $this->id,
            ]);
        }

        // If Humhub is guest
        return $this->page_url;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        $space = $this->content->container;

        $attributes = [
            'message' => $this->website->title,
            // url comment because make solr crash
            // 'page_url' => $space->createUrl('/external-websites/page?title='.urlencode($this->website->title).'&pageId='.$this->id),
            'user' => $this->getPostAuthorName()
        ];

        $this->trigger(self::EVENT_SEARCH_ADD, new \humhub\modules\search\events\SearchAddEvent($attributes));

        return $attributes;
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

    public function getIcon()
    {
        if (!empty($this->website->icon)) {
            return $this->website->icon;
        }
        return 'fa-desktop';
    }


    /**
     * @inheritdoc
     * Set created_by to external_websites_website creator
     */
    public function beforeSave($insert)
    {
        $this->created_by = $this->website->created_by;
        $this->content->created_by = $this->created_by;

        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     * For all users that receive notifications for new content, make them follow the content to sent notifications if new comments, as this module doesn't send notification for each new content ($this->silentContentCreation value is true)
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $space = $this->content->container;
            foreach ($space->memberships as $membership) {
                if ($membership->send_notifications) {
                    $this->follow($membership->user_id, true);
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

}