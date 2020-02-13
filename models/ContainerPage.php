<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use Yii;


class ContainerPage extends \yii\db\ActiveRecord
{

    const NAV_CLASS_TOPNAV = 'TopMenuWidget';
    const NAV_CLASS_ACCOUNTNAV = 'AccountMenuWidget';
    const NAV_CLASS_EMPTY = 'WithOutMenu';
    const NAV_CLASS_DIRECTORY = 'DirectoryMenu';

    const STATE_DISABLED = 'Disabled';
    const STATE_ADMIN_ONLY = 'AdminOnly';
    const STATE_ALL_USERS = 'AllUsers';

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
            'page_url' => 'Page URL',
            'iframe_first_url' => 'iFrame first URL',
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
           [['space_id', 'page_url', 'iframe_first_url'], 'required'],
           [['title', 'icon', 'page_url', 'iframe_first_url', 'target', 'state', 'comments_global_state'], 'string'],
           [['space_id', 'sort_order'], 'integer'],
       ];
    }


    public function getContainerUrl()
    {
        return $this
            ->hasMany(ContainerUrl::className(), ['container_page_id' => 'id']);
    }

}