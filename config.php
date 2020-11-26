<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

use humhub\modules\externalWebsites\Events;

return [
    'id' => 'external-websites',
    'class' => 'humhub\modules\externalWebsites\Module',
    'namespace' => 'humhub\modules\externalWebsites',
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
