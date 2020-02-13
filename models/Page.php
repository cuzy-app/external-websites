<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models;

use Yii;


class Page extends \yii\db\ActiveRecord
{

    const TARGET_TOPNAV = 'TopMenuWidget';
    const TARGET_ACCOUNTNAV = 'AccountMenuWidget';
    const TARGET_EMPTY = 'WithOutMenu';
    const TARGET_DIRECTORY = 'DirectoryMenu';

    const STATE_DISABLED = 'Disabled';
    const STATE_ADMINS = 'Admins';
    const STATE_PUBLIC = 'Public';

    const COMMENTS_GLOBAL_STATE_DISABLED = 'Disabled';
    const COMMENTS_GLOBAL_STATE_ENABLED = 'Enabled';
    const COMMENTS_GLOBAL_STATE_CLOSED = 'Closed';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iframe_page';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
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
           [['start_url'], 'required'],
           [['title', 'icon', 'start_url', 'target', 'state', 'comments_global_state'], 'string'],
           [['sort_order'], 'integer'],
       ];
    }


    public function getUrl()
    {
        return $this
            ->hasMany(Url::className(), ['page_id' => 'id']);
    }

}