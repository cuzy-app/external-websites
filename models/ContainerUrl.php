<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
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
    public $autoAddToWall = false;

    public $canMove = true;


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
            'container_page_id' => 'Container page id',
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
           [['url', 'comments_state'], 'string'],
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
        return $this->containerPage['title'];
    }

    public function getContentDescription()
    {
        return $this->containerPage['title'];
    }

    // Searchable Attributes / Informations
    public function getSearchAttributes()
    {
        return $this->containerPage['title'];
    }

    public function getIcon()
    {
        return $this->containerPage['icon'];
    }

}