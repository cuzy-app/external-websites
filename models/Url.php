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


class Url extends ContentActiveRecord implements Searchable
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
        return 'url';
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
            'page_id' => 'Page id',
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
           [['page_id', 'url'], 'required'],
           [['url', 'title', 'comments_state'], 'string'],
           [['page_id'], 'integer'],
       ];
    }

    public function getPage()
    {
        return $this
            ->hasOne(Page::className(), ['id' => 'page_id']);
    }



    public function getContentName()
    {
        return $this->page['title'];
    }

    public function getContentDescription()
    {
        return $this->page['title'];
    }

    // Searchable Attributes / Informations
    public function getSearchAttributes()
    {
        return $this->page['title'];
    }

    public function getIcon()
    {
        return $this->page['icon'];
    }

}