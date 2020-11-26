<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use Yii;
use humhub\modules\space\models\Space;

/**
 * Websites to which we want to add addons to the pages (comments, like, files, etc.)
 * 
 * @property integer $space_id
 * @property string $title
 * @property string $Icon Fontawesome
 * @property string $first_page_url
 * @property boolean $show_in_menu
 * @property integer $sort_order 
 * @property string $remove_from_url_title
 * @property boolean $hide_sidebar If Enterprise theme
 * @property integer $default_content_visibility Default value for the Content created ; can be humhub\modules\content\models\Content::VISIBILITY_PRIVATE or Content::VISIBILITY_PUBLIC or Content::VISIBILITY_OWNER
 * @property integer $default_content_archived Default value for the Content created ; can be 0 or 1 (if 1, new comments are disabled) : humhub\modules\content\models\Content->archive(), humhub\modules\content\models\Content->unarchive()
 */
class Website extends \humhub\components\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'external_websites_website';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'space_id' => 'Space Id',
            'title' => 'Title',
            'icon' => 'Icon',
            'first_page_url' => 'Website first page URL',
            'show_in_menu' => 'Show in space menu',
            'sort_order' => 'Sort order',
            'remove_from_url_title' => 'Text to remove from URL title',
            'hide_sidebar' => 'Hide sidebar',
            'default_content_visibility' => 'Content visibility default value',
            'default_content_archived' => 'Content archived default value',
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
           [['space_id', 'first_page_url'], 'required'],
           [['title', 'icon', 'first_page_url', 'remove_from_url_title'], 'string'],
           [['space_id', 'sort_order', 'default_content_visibility', 'default_content_archived'], 'integer'],
           [['show_in_menu', 'hide_sidebar'], 'boolean'],
       ];
    }


    public function getPages()
    {
        return $this
            ->hasMany(Page::class, ['website_id' => 'id']);
    }

    public function getSpace()
    {
        return $this
            ->hasOne(Space::class, ['id' => 'space_id']);
    }


    public function beforeDelete()
    {
        // TBD : if last one of the space, remove all Page rows and related content

        // C’est pas très simple à cause des contenus qui peuvent être partagés par plusieurs onglets.
        // Si on supprime un onglet, on pourra supprimer tout le contenu lié à cet onglet qui n’a pas de commentaire, mais s’il y a des commentaires, faudra juste mettre à NULL website_id en attendant qu’il soit éventuelle réaffecté à un autre onglet.
        // Par contre, quand on supprimera le dernier onglet, on pourra supprimer tous les contenus créés par le module iframe, de la même manière que c’est déjà le cas quand on désactive le module.

        return parent::beforeDelete();
    }
}