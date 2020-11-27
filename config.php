<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use humhub\modules\externalWebsites\Events;
use humhub\modules\space\widgets\Menu;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\widgets\BaseMenu;


return [
    'id' => 'external-websites',
    'class' => 'humhub\modules\externalWebsites\Module',
    'namespace' => 'humhub\modules\externalWebsites',
    'events' => [
    	[
    		'class' => Menu::class,
    		'event' => Menu::EVENT_INIT,
    		'callback' => [Events::class, 'onSpaceMenuInit'],
    	],
        [
            'class' => WallStreamFilterNavigation::class,
            'event' =>  WallStreamFilterNavigation::EVENT_BEFORE_RUN,
            'callback' => [Events::class, 'onStreamFilterBeforeRun']
        ],
    	[
		    'class' => WallStreamQuery::class,
		    'event' =>  WallStreamQuery::EVENT_BEFORE_FILTER,
		    'callback' => [Events::class, 'onStreamFilterBeforeFilter'],
		],
        [
            'class' => HeaderControlsMenu::class,
            'event' => BaseMenu::EVENT_INIT,
            'callback' => [Events::class, 'onSpaceAdminMenuInit']],
    ]
];
?>
