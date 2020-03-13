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

    // comments_global_state must be one of the ContainerUrl const COMMENTS_STATE_xxx

    // visibility can be humhub\modules\content\models\Content::VISIBILITY_PRIVATE or Content::VISIBILITY_PUBLIC

    
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
            'remove_from_url_title' => 'Text to remove from URL title',
            'content_archived' => 'Content archived',
            'hide_sidebar' => 'Hide sidebar', // Enterprise theme
            'show_widget' => 'Show Widget',
            'comments_global_state' => 'Comments global state',
            'visibility' => 'Visibility',
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
           [['title', 'icon', 'start_url', 'target', 'remove_from_url_title', 'comments_global_state'], 'string'],
           [['space_id', 'sort_order', 'content_archived', 'show_widget', 'visibility'], 'integer'],
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