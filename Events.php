<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites;

use Firebase\JWT\JWT;
use humhub\modules\externalWebsites\assets\EmbeddedAssets;
use humhub\modules\externalWebsites\assets\SpaceSettingsAssets;
use humhub\modules\externalWebsites\models\filters\ExternalWebsitesSpaceStreamFilter;
use humhub\modules\externalWebsites\models\forms\SpaceSettingsForm;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\widgets\AddClassToHtmlTag;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\models\Group;
use humhub\widgets\LayoutAddons;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;


class Events
{
    const FILTER_BLOCK_EXTERNAL_WEBSITE = 'external-websites';
    const FILTER_EXTERNAL_WEBSITE = 'external-websites';


    public static function onSpaceMenuInit($event)
    {
        $menu = $event->sender;

        // Get current page URL if exists
        $currentId = Yii::$app->request->get('id');

        /** @var Space $space */
        $space = $event->sender->space;

        if ($space !== null && $space->moduleManager->isEnabled('external-websites')) {

            // Get pages
            $websites = Website::find()
                ->where(['space_id' => $space->id])
                ->andWhere(['show_in_menu' => 1])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            foreach ($websites as $website) {
                $menu->addEntry(new MenuLink([
                    'label' => $website->title,
                    'url' => $website->getUrl(),
                    'icon' => $website->icon,
                    'isActive' => (
                        MenuLink::isActiveState('external-websites', 'website', 'index')
                        && $currentId
                        && $currentId == $website->id
                    ),
                    'htmlOptions' => $website->humhub_is_embedded ? ['target' => '_blank'] : [],
                    'isVisible' => true,
                ]));
            }
        }
    }


    public static function onStreamFilterBeforeRun($event)
    {
        if (
            !isset(Yii::$app->controller->contentContainer)
            || Yii::$app->controller->module->id !== 'space'
            || ($space = Yii::$app->controller->contentContainer) === null
            || !$space->moduleManager->isEnabled('external-websites')
        ) {
            return;
        }

        /** @var $wallFilterNavigation WallStreamFilterNavigation */
        $wallFilterNavigation = $event->sender;

        // Add a new filter block to the last filter panel
        $wallFilterNavigation->addFilterBlock(
            self::FILTER_BLOCK_EXTERNAL_WEBSITE, [
            'title' => Yii::t('ExternalWebsitesModule.base', 'Filter'),
            'sortOrder' => 300
        ],
            (defined('\humhub\modules\stream\widgets\WallStreamFilterNavigation::PANEL_COLUMN_2') ? WallStreamFilterNavigation::PANEL_COLUMN_2 : WallStreamFilterNavigation::PANEL_POSITION_CENTER) // TODO: when module compatibility minimal Humhub version is 1.12, keep only static::PANEL_COLUMN_2
        );

        // Get pages
        $websites = Website::find()
            ->where(['space_id' => $space->id])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $sortOrder = 0;
        foreach ($websites as $website) {
            $sortOrder++;

            // Add a filters to the new filter block
            $wallFilterNavigation->addFilter(
                [
                    'id' => ExternalWebsitesSpaceStreamFilter::FILTER_SURVEY_STATE_PREFIX . $website->id,
                    'title' => Yii::t(
                        'ExternalWebsitesModule.base',
                        '{title}: show comments',
                        ['{title}' => $website->title]
                    ),
                    'sortOrder' => $sortOrder,
                ],
                self::FILTER_BLOCK_EXTERNAL_WEBSITE
            );
        }
    }


    /**
     * Adds filters
     */
    public static function onStreamFilterBeforeFilter($event)
    {
        // if single content (contentId in URL)
        if (!empty($event->sender->contentId)) {
            return;
        }

        /** @var $streamQuery WallStreamQuery */
        $streamQuery = $event->sender;

        // If in a space
        if (isset(Yii::$app->controller->contentContainer)) {
            $space = Yii::$app->controller->contentContainer;
            if ($space instanceof Space && $space->moduleManager->isEnabled('external-websites')) {
                // Add a new filterHandler to WallStreamQuery
                $streamQuery->filterHandlers[] = ExternalWebsitesSpaceStreamFilter::class;
            }
        }
    }


    public static function onSpaceAdminMenuInit($event)
    {
        /** @var HeaderControlsMenu $headerMenu */
        $headerMenu = $event->sender;

        /* @var $space Space */
        $space = $headerMenu->space;

        if ($space->moduleManager->isEnabled('external-websites') && $space->isAdmin()) { // Don't move in 'isVisible' as it doesn't work in all cases and because the "if" costs less
            $headerMenu->addEntry(new MenuLink([
                'label' => Yii::t('ExternalWebsitesModule.base', 'Manage external websites & settings'),
                'url' => $space->createUrl('/external-websites/manage/websites'),
                'icon' => 'desktop',
                'isActive' => MenuLink::isActiveState('external-websites', 'manage', 'websites'),
                'isVisible' => true,
            ]));
        }
    }

    public static function onViewBeginBody($event)
    {
        /** @var LayoutAddons $layoutAddons */
        $view = $event->sender;

        $module = Yii::$app->getModule('external-websites');

        if ($module->registerAssetsIfHumhubIsEmbedded) {
            // TODO: replace echo
            echo AddClassToHtmlTag::widget();
            EmbeddedAssets::register(Yii::$app->view);
        }
    }

    public static function onControllerInit($event)
    {
        if (Yii::$app->user->isGuest || !method_exists(Yii::$app->request, 'get')) {
            return;
        }
        // If JWT token in URL param, try adding groups to current user
        $token = Yii::$app->request->get('token');
        if ($token = Yii::$app->request->get('token')) {
            self::tryAddingGroupsToUser($token);
        }
    }

    /**
     * @param $token HS512 JWT token
     * @throws HttpException
     * If logged in AND JWT token param in URL, check permission and add current user to groups
     */
    private static function tryAddingGroupsToUser($token)
    {
        $module = Yii::$app->getModule('external-websites');

        // If jwtKey property is defined in configuration
        if (!empty($module->jwtKey)) {
            try {
                $validData = JWT::decode($token, $module->jwtKey, ['HS512']);

                // If authentification successful
                if (isset($validData->groupsId) && is_array($validData->groupsId)) {

                    // Add current user to groups
                    foreach (Group::findAll($validData->groupsId) as $group) {
                        if (!$group->isMember(Yii::$app->user->identity)) {
                            $group->addUser(Yii::$app->user->identity);
                        }
                    }
                }
            } catch (Exception $e) {
                throw new HttpException(401, $e->getMessage());
            }
        }
    }

    public static function onContentContainerControllerBeforeAction($event)
    {
        // If the current container is a space, try redirecting space content
        $contentContainer = $event->sender->contentContainer;
        if ($contentContainer !== null && $contentContainer instanceof Space) {
            self::tryRedirectingSpaceContent($contentContainer);
        }
    }

    /**
     * @param $contentContainer
     * If this space has a setting to redirect contents URLs to an external website, do the redirection
     */
    private static function tryRedirectingSpaceContent($contentContainer)
    {
        $settings = Yii::$app->getModule('external-websites')->settings->space($contentContainer);

        $urlToRedirect = $settings->get('urlToRedirect');
        if (!empty($urlToRedirect)) {
            $urlToRedirect = str_replace('{humhubUrl}', urlencode(Url::current([], true)), $urlToRedirect);
        }

        $preventLeavingSpace = $settings->get('preventLeavingSpace', (new SpaceSettingsForm)->preventLeavingSpace);

        if (!empty($urlToRedirect) || $preventLeavingSpace) {
            SpaceSettingsAssets::register(Yii::$app->view);
            Yii::$app->view->registerJsConfig('externalWebsites.SpaceSettings', [
                'urlToRedirect' => $urlToRedirect,
                'preventLeavingSpace' => $preventLeavingSpace,
            ]);
        }
    }
}