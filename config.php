<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

return [
    'id' => 'iframe',
    'class' => 'humhub\modules\iframe\Module',
    'namespace' => 'humhub\modules\iframe',
    'events' => [
    	[
    		'class' => \humhub\modules\space\widgets\Menu::class,
    		'event' => \humhub\modules\space\widgets\Menu::EVENT_INIT,
    		'callback' => ['\humhub\modules\iframe\Events', 'onSpaceMenuInit'],
    	],
        [
            'class' => \humhub\modules\stream\widgets\WallStreamFilterNavigation::class,
            'event' =>  \humhub\modules\stream\widgets\WallStreamFilterNavigation::EVENT_BEFORE_RUN,
            'callback' => ['\humhub\modules\iframe\Events', 'onStreamFilterBeforeRun']
        ],
    	[
		    'class' => \humhub\modules\stream\models\WallStreamQuery::class,
		    'event' =>  \humhub\modules\stream\models\WallStreamQuery::EVENT_BEFORE_FILTER,
		    'callback' => ['\humhub\modules\iframe\Events', 'onStreamFilterBeforeFilter'],
		],
    ]
];
?>
