<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use Yii;
use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\search\interfaces\Searchable;
use yii\db\Expression;


class ContainerUrl extends ContentActiveRecord implements Searchable
{
    public $moduleId = 'iframe';

    const NAV_CLASS_SPACE_NAV = 'SpaceMenu';
    const NAV_CLASS_EMPTY = 'WithOutMenu';

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
            'iframe_url' => 'iFrame URL',
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
           [['container_page_id', 'iframe_url'], 'required'],
           [['iframe_url', 'comments_state'], 'string'],
           [['container_page_id'], 'integer'],
       ];
    }

    public function getContainerPage()
    {
        return $this
            ->hasOne(ContainerPage::className(), ['id' => 'container_page_id']);
    }
}