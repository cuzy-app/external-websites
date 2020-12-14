<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites;

use Yii;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\filters\ExternalWebsitesSpaceStreamFilter;
use humhub\modules\externalWebsites\assets\EmbeddedAssets;
use humhub\modules\externalWebsites\assets\RedirectionsAssets;
use humhub\modules\externalWebsites\widgets\AddClassToHtmlTag;
use humhub\modules\space\models\Space;


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
        $contentContainer = $event->sender->contentContainer;

        if ($contentContainer === null || get_class($contentContainer) !== Space::class) {
            return;
        }

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