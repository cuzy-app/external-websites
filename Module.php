<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use Yii;
use yii\helpers\Url;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;


class Module extends ContentContainerModule
{
    
    /**
     * @var string defines the icon
     */
    public $icon = 'fa-external-link';

    /**
     * @var string defines path for resources, including the screenshots path for the marketplace
     */
    public $resourcesPath = 'resources';


    /**
     * If an auth client has attribute autoLogin set to true, this module will auto log the user to the corresponding Identity provider (SSO)
     */
    public $tryAutoLogin = true;


    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        // check permission management to customize access to this module
        return [];
    }


    /**
     * @inheritdoc
     */
    public function enable()
    {
        return parent::enable();
    }


    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (ContainerUrl::find()->all() as $containerUrl) {
            $containerUrl->delete();
        }

        foreach (ContainerPage::find()->all() as $containerPage)
        {
            $containerPage->delete();
        }

        return parent::disable();
    }


    /**
     * @inheritdoc
     */
    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        return parent::enableContentContainer($container);
    }


    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (ContainerUrl::find()->contentContainer($container)->all() as $containerUrl) {
            $containerUrl->delete();
        }

        foreach (ContainerPage::findAll(['space_id' => $container['id']]) as $containerPage)
        {
            $containerPage->delete();
        }
    }


    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('IframeModule.base', 'iFrame');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        if ($container instanceof Space) {
            return Yii::t('IframeModule.base', 'This module creates pages containing an iframed website where members can comment.');
        }
    }
}
