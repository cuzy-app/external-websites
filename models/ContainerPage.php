<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models;

use Yii;


class ContainerPage extends \humhub\components\ActiveRecord
{

    const TARGET_SPACE_NAV = 'SpaceMenu';
    const TARGET_EMPTY = 'WithOutMenu';
    
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
            'target' => 'Target', // self::TARGET_SPACE_NAV or self::TARGET_EMPTY
            'sort_order' => 'Sort order', // In the menu
            'remove_from_url_title' => 'Text to remove from URL title',
            'default_hide_in_stream' => 'Hide contents in stream', // If hidden, the contents can be seen with the stream filter
            'hide_sidebar' => 'Hide sidebar', // Enterprise theme
            'show_widget' => 'Show Widget',
            'visibility' => 'Content visibility', // Default value for the Content created ; can be humhub\modules\content\models\Content::VISIBILITY_PRIVATE or Content::VISIBILITY_PUBLIC or Content::VISIBILITY_OWNER
            'archived' => 'Content archived', // Default value for the Content created ; can be 0 or 1 (new comments are disabled) : humhub\modules\content\models\Content->archive(), humhub\modules\content\models\Content->unarchive()
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
           [['title', 'icon', 'start_url', 'target', 'remove_from_url_title'], 'string'],
           [['space_id', 'sort_order', 'default_hide_in_stream', 'show_widget', 'visibility', 'archived'], 'integer'],
       ];
    }


    public function getContainerUrl()
    {
        return $this
            ->hasMany(ContainerUrl::class, ['container_page_id' => 'id']);
    }


    public function beforeDelete()
    {
        // TBD : if last one of the space, remove all ContainerUrl rows and related content

        // C’est pas très simple à cause des contenus qui peuvent être partagés par plusieurs onglets.
        // Si on supprime un onglet, on pourra supprimer tout le contenu lié à cet onglet qui n’a pas de commentaire, mais s’il y a des commentaires, faudra juste mettre à NULL container_page_id en attendant qu’il soit éventuelle réaffecté à un autre onglet.
        // Par contre, quand on supprimera le dernier onglet, on pourra supprimer tous les contenus créés par le module iframe, de la même manière que c’est déjà le cas quand on désactive le module.

        return parent::beforeDelete();
    }
}