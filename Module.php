<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    
    /**
     * @var string defines the icon
     */
    public $icon = 'fa-external-link';

    public $resourcesPath = 'resources';


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
    public function getConfigUrl()
    {
        return Url::to([
            '/iframe/config'
        ]);
    }
}
