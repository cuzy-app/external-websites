<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models;

use Yii;
use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\search\interfaces\Searchable;
use yii\db\Expression;


class ContainerUrl extends ContentActiveRecord implements Searchable
{
    public $moduleId = 'iframe';

    const COMMENTS_STATE_DISABLED = 'Disabled';
    const COMMENTS_STATE_ENABLED = 'Enabled';
    const COMMENTS_STATE_CLOSED = 'Closed';


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
            'comments_state' => 'URL comments State',
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
           [['url', 'title', 'comments_state'], 'string'],
           [['container_page_id'], 'integer'],
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

    // Searchable Attributes / Informations
    public function getSearchAttributes()
    {
        return $this->containerPage['title'];
    }

    public function getIcon()
    {
        if (!empty($this->containerPage['icon'])) {
            return $this->containerPage['icon'];
        }
        return 'fa-external-link-square';
    }

}