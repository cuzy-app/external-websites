<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models;

use Yii;
use yii\db\Expression;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\user\models\User;


class ContainerUrl extends ContentActiveRecord implements Searchable
{
    public $moduleId = 'iframe';

    /**
     * @inheritdoc
     */
    public $wallEntryClass = "humhub\modules\iframe\widgets\WallEntry";

    public $streamChannel = 'default';

    public $canMove = false;

    /**
     * @var boolean should the originator automatically follows this content when saved.
     */
    public $autoFollow = false;

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
        return 'iframe_container_url';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'url' => 'iFrame URL',
            'title' => 'Title',
            'container_page_id' => 'Container page id',
            'hide_in_stream' => 'Hide in stream',
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
           [['container_page_id', 'url'], 'required'],
           [['url', 'title'], 'string'],
           [['container_page_id', 'hide_in_stream'], 'integer'],
       ];
    }


    public function getContainerPage()
    {
        return $this
            ->hasOne(ContainerPage::className(), ['id' => 'container_page_id']);
    }


    public function getContentName()
    {
        if (!empty($this->containerPage['title'])) {
            return $this->containerPage['title'];
        }
        return Yii::t('IframeModule.base', 'iFrame');
    }

    public function getContentDescription()
    {
        return $this['title'];
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        $attributes = [
            'message' => $this->containerPage['title'],
            'url' => $this->url,
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
        if (!empty($this->containerPage['icon'])) {
            return $this->containerPage['icon'];
        }
        return 'fa-external-link-square';
    }



    /**
     * @inheritdoc
     * For all users that receive notifications for new content, make them follow the content to sent notifications if new comments, as this module doesn't send notification for each new content to avoid huge amount of notifications (a new content is created for each iframed page visited !)
     */
    public function afterSave($insert, $changedAttributes)
    {
        $spaceId = $this->initContent->contentContainer['pk'];
        $space = Space::findOne(['id' => $spaceId]);
        if ($space !== null) {
            foreach ($space->memberships as $membership) {
                if ($membership->send_notifications) {
                    $this->follow($membership['user_id'], true);
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

}