<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

use humhub\modules\iframe\Events;

return [
    'id' => 'iframe',
    'class' => 'humhub\modules\iframe\Module',
    'namespace' => 'humhub\modules\iframe',
    'events' => [
    	[
    		'class' => \humhub\modules\space\widgets\Menu::class,
    		'event' => \humhub\modules\space\widgets\Menu::EVENT_INIT,
    		'callback' => [Events::class, 'onSpaceMenuInit'],
    	],
        [
            'class' => \humhub\modules\stream\widgets\WallStreamFilterNavigation::class,
            'event' =>  \humhub\modules\stream\widgets\WallStreamFilterNavigation::EVENT_BEFORE_RUN,
            'callback' => [Events::class, 'onStreamFilterBeforeRun']
        ],
    	[
		    'class' => \humhub\modules\stream\models\WallStreamQuery::class,
		    'event' =>  \humhub\modules\stream\models\WallStreamQuery::EVENT_BEFORE_FILTER,
		    'callback' => [Events::class, 'onStreamFilterBeforeFilter'],
		],
    ]
];
?>
