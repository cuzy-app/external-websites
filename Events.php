<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites;

use Firebase\JWT\JWT;
use Yii;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\filters\ExternalWebsitesSpaceStreamFilter;
use humhub\modules\externalWebsites\assets\EmbeddedAssets;
use humhub\modules\externalWebsites\assets\RedirectionsAssets;
use humhub\modules\externalWebsites\widgets\AddClassToHtmlTag;
use humhub\modules\space\models\Space;
use yii\web\HttpException;


class Events
{
    const FILTER_BLOCK_EXTERNAL_WEBSITE = 'external-websites';
    const FILTER_EXTERNAL_WEBSITE = 'external-websites';


    public static function onSpaceMenuInit($event)
    {
        // Get current page URL if exists
        $currentTitle = Yii::$app->request->get('title');

        $space = $event->sender->space;

        if ($space !== null && $space->isModuleEnabled('external-websites')) {

            // Get pages
            $websites = Website::find()
                ->where(['space_id' => $space->id])
                ->andWhere(['show_in_menu' => 1])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            foreach ($websites as $website) {
                $event->sender->addItem([
                    'label' => $website->title,
                    'group' => 'modules',
                    'url' => $website->url,
                    'icon' => '<i class="fa '.$website->icon.'"></i>',
                    'isActive' => (
                        MenuLink::isActiveState('external-websites', 'website', 'index')
                        && $currentTitle !== null
                        && $currentTitle == $website->title
                    ),
                    'htmlOptions' => $website->humhub_is_embedded ? ['target' => '_blank'] : [],
                ]);
            }
        }
    }


    public static function onStreamFilterBeforeRun ($event)
    {
        if (!isset(Yii::$app->controller->contentContainer)) {
            return;
        }
        $space = Yii::$app->controller->contentContainer;
        if ($space !== null && $space->isModuleEnabled('external-websites')) {

            /** @var $wallFilterNavigation WallStreamFilterNavigation */
            $wallFilterNavigation = $event->sender;
        
            // Add a new filter block to the last filter panel
            $wallFilterNavigation->addFilterBlock(
                static::FILTER_BLOCK_EXTERNAL_WEBSITE, [
                    'title' => Yii::t('ExternalWebsitesModule.base', 'Filter'),
                    'sortOrder' => 300
                ],
                WallStreamFilterNavigation::PANEL_POSITION_CENTER
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
                        'id' => 'website_id_'.$website->id,
                        'title' => Yii::t(
                            'ExternalWebsitesModule.base',
                            '{title}: show comments',
                            ['{title}' => $website->title]
                        ),
                        'sortOrder' => $sortOrder,
                    ],
                    static::FILTER_BLOCK_EXTERNAL_WEBSITE
                );
            }
        }
    }


    /**
     * Adds filters
     */
    public static function onStreamFilterBeforeFilter ($event)
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
            if ($space !== null && $space->isModuleEnabled('external-websites')) {
                // Add a new filterHandler to WallStreamQuery
                $streamQuery->filterHandlers[] = ExternalWebsitesSpaceStreamFilter::class;
            }
        }
    }


    public static function onSpaceAdminMenuInit($event)
    {
        try {
            /* @var $space \humhub\modules\space\models\Space */
            $space = $event->sender->space;
            if ($space->isModuleEnabled('external-websites') && $space->isAdmin() && $space->isMember()) {
                $event->sender->addItem([
                    'label' => Yii::t('ExternalWebsitesModule.base', 'Manage external websites & settings'),
                    'group' => 'admin',
                    'url' => $space->createUrl('/external-websites/manage/websites'),
                    'icon' => '<i class="fa fa-desktop"></i>',
                    'isActive' => MenuLink::isActiveState('external-websites', 'manage', 'websites'),
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onViewBeginBody($event)
    {
        /** @var LayoutAddons $layoutAddons */
        $view = $event->sender;

        $module = Yii::$app->getModule('external-websites');

        if ($module->registerAssetsIfHumhubIsEmbedded) {
            echo AddClassToHtmlTag::widget();
            EmbeddedAssets::register(Yii::$app->view);
        }
    }

    public static function onContentContainerControllerBeforeAction($event)
    {
        // If the current container is a space, try redirecting space content
        $contentContainer = $event->sender->contentContainer;
        if ($contentContainer === null || get_class($contentContainer) !== Space::class) {
            self::tryRedirectingSpaceContent($contentContainer);
        }
    }

    public static function onControllerInit($event)
    {
        // If autologin in URL param, try auto login
        if (Yii::$app->user->isGuest && Yii::$app->request->get('autoLogin')) {
            self::tryAutoLogin();
        }

        // If JWT token in URL param, try adding groups to current user
        $token = Yii::$app->request->get('token');
        if (!Yii::$app->user->isGuest && !empty($token)) {
            self::tryAddingGroupsToUser($token);
        }
    }


        /**
     * @return mixed
     * If autoLogin param true in URL, try auto login
     */
    private static function tryAutoLogin()
    {
        // If an auth client has attribute autoLogin set to true, this module will auto log the user to the corresponding Identity provider (SSO)
        foreach (Yii::$app->authClientCollection->clients as $authclient) {
            if (isset($authclient->autoLogin) && $authclient->autoLogin) {
                // Redirect to Identity Provider
                if (method_exists($authclient, 'redirectToBroker')) {
                    return $authclient->redirectToBroker(true);
                }
            }
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
                    foreach(Group::findAll($validData->groupsId) as $group)
                        if (!$group->isMember(Yii::$app->user->identity)) {
                            $group->addUser(Yii::$app->user->identity);
                        }
                }
            } catch (Exception $e) {
                throw new HttpException(401, $e->getMessage());
            }
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
        if (!empty($settings->get('urlToRedirect'))) {
            $urlToRedirect = str_replace('{humhubUrl}', urlencode(\yii\helpers\Url::current([], true)), $urlToRedirect);
        }

        RedirectionsAssets::register(Yii::$app->view);
        Yii::$app->view->registerJsConfig('externalWebsites.Redirections', [
            'urlToRedirect' => $urlToRedirect,
        ]);
    }

}