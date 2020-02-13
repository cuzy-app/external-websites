<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models;

use Yii;


class ContainerPage extends \yii\db\ActiveRecord
{

    const TARGET_SPACE_NAV = 'SpaceMenu';
    const TARGET_EMPTY = 'WithOutMenu';

    const STATE_DISABLED = 'Disabled';
    const STATE_ADMINS = 'Admins';
    const STATE_MODERATORS = 'Moderators';
    const STATE_MEMBERS = 'Members';
    const STATE_PUBLIC = 'Public';

    const COMMENTS_GLOBAL_STATE_DISABLED = 'Disabled';
    const COMMENTS_GLOBAL_STATE_ENABLED = 'Enabled';
    const COMMENTS_GLOBAL_STATE_CLOSED = 'Closed';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iframe_container_page';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'space_id' => 'Space Id',
            'title' => 'Title',
            'icon' => 'Icon',
            'start_url' => 'iFrame start URL',
            'target' => 'Target',
            'sort_order' => 'Sort order',
            'state' => 'State',
            'comments_global_state' => 'Comments global state',
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
           [['space_id', 'start_url'], 'required'],
           [['title', 'icon', 'start_url', 'target', 'state', 'comments_global_state'], 'string'],
           [['space_id', 'sort_order'], 'integer'],
       ];
    }


    public function getContainerUrl()
    {
        return $this
            ->hasMany(ContainerUrl::className(), ['container_page_id' => 'id']);
    }


    public function beforeDelete()
    {
        foreach ($this->containerUrl as $containerUrl) {
            $containerUrl->delete();
// TBD : related content deleted ?
        }

        return parent::beforeDelete();
    }
}