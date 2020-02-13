<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use humhub\modules\space\widgets\Menu;

return [
    'id' => 'iframe',
    'class' => 'humhub\modules\iframe\Module',
    'namespace' => 'humhub\modules\iframe',
    'events' => [
    	['class' => Menu::class, 'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\iframe\Events', 'onSpaceMenuInit']],
    ]
];
?>
