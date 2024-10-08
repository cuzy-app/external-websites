<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\components\Controller;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\externalWebsites\Events;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\space\widgets\Menu as SpaceMenu;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\ui\view\components\View;

/** @noinspection MissedFieldInspection */
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
            'event' => WallStreamFilterNavigation::EVENT_BEFORE_RUN,
            'callback' => [Events::class, 'onStreamFilterBeforeRun'],
        ],
        [
            'class' => WallStreamQuery::class,
            'event' => WallStreamQuery::EVENT_BEFORE_FILTER,
            'callback' => [Events::class, 'onStreamFilterBeforeFilter'],
        ],
        [
            'class' => HeaderControlsMenu::class,
            'event' => HeaderControlsMenu::EVENT_INIT,
            'callback' => [Events::class, 'onSpaceAdminMenuInit'],
        ],
        [
            'class' => View::class,
            'event' => View::EVENT_BEGIN_BODY,
            'callback' => [Events::class, 'onViewBeginBody'],
        ],
        [
            'class' => Controller::class,
            'event' => Controller::EVENT_INIT,
            'callback' => [Events::class, 'onControllerInit'],
        ],
        [
            'class' => ContentContainerController::class,
            'event' => ContentContainerController::EVENT_BEFORE_ACTION,
            'callback' => [Events::class, 'onContentContainerControllerBeforeAction'],
        ],
    ],
];
