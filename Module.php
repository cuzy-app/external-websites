<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
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

    public function disable()
    {
        return parent::disable();
        // what needs to be done if module is completely disabled?
    }

    public function enable()
    {
        return parent::enable();
        // what needs to be done if module is enabled?
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
