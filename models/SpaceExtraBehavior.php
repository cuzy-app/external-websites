<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use Yii;
use humhub\modules\space\models\Space;

/**
 * Websites to which we want to add addons to the pages (comments, like, files, etc.)
 *
 * @property integer $space_id
 * @property boolean hide  Hide the space to all users except network and space administrators
 * @property string url_to_redirect URL of the external website to redirect all URLs of the space (if empty or null, no redirection)
 */
class SpaceExtraBehavior extends \humhub\components\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'external_websites_space_extra_behavior';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'space_id' => Yii::t('ExternalWebsitesModule.base', 'Space ID'),
            'hide' => Yii::t('ExternalWebsitesModule.base', 'Hide the space to all users except network and space administrators'),
            'url_to_redirect' => Yii::t('ExternalWebsitesModule.base', 'URL of the external website to redirect all URLs of the space (if empty, no redirection)'),
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
            [['space_id'], 'required'],
            [['url_to_redirect'], 'string'],
            [['hide'], 'boolean'],
        ];
    }
}