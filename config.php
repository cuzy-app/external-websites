<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use humhub\modules\externalWebsites\Events;
use humhub\modules\space\widgets\Menu as SpaceMenu;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\ui\menu\widgets\Menu as UiMenu;
use humhub\modules\ui\view\components\View;
use humhub\modules\content\components\ContentContainerController;
use humhub\components\Controller;

return [
    'id' => 'external-websites',
    'class' => 'humhub\modules\externalWebsites\Module',
    'namespace' => 'humhub\modules\externalWebsites',
    'events' => [
    	[
    		'class' => SpaceMenu::class,
    		'event' => SpaceMenu::EVENT_INIT,
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
            'event' => UiMenu::EVENT_INIT,
            'callback' => [Events::class, 'onSpaceAdminMenuInit']
		],
		[
			'class' => View::class,
			'event' => View::EVENT_BEGIN_BODY,
			'callback' => [Events::class, 'onViewBeginBody']
		],
		[
			'class' => ContentContainerController::class,
			'event' => ContentContainerController::EVENT_BEFORE_ACTION,
			'callback' => [Events::class, 'onContentContainerControllerBeforeAction']
		],
		[
			'class' => Controller::class,
			'event' => Controller::EVENT_INIT,
			'callback' => [Events::class, 'onControllerInit']
		],
	]
];
?>
