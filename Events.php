<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites;

use Yii;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\filters\ExternalWebsitesSpaceStreamFilter;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;


class Events
{
    const FILTER_BLOCK_EXTERNAL_WEBSITE = 'external-websites';
    const FILTER_EXTERNAL_WEBSITE = 'external-websites';


    public static function onSpaceMenuInit($event)
    {
        // Get current page URL if exists
        $currentPageTitle = Yii::$app->request->get('title');

        $space = $event->sender->space;

        if ($space !== null && $space->isModuleEnabled('external-websites')) {

            // Get pages
            $websites = Website::find()
                ->where(['space_id' => $space['id']])
                ->andWhere(['show_in_menu' => true])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            foreach ($websites as $website) {
                $event->sender->addItem([
                    'label' => $website['title'],
                    'group' => 'modules',
                    'url' => $website->url,
                    'icon' => '<i class="fa '.$website['icon'].'"></i>',
                    'isActive' => (
                        Yii::$app->controller->module
                        && Yii::$app->controller->module->id == 'external-websites'
                        && Yii::$app->controller->id = 'page'
                        && $currentPageTitle !== null
                        && $currentPageTitle == $website['title']
                    ),
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
                    'title' => Yii::t('ExternalWebsitesModule.model', 'Filter'),
                    'sortOrder' => 300
                ],
                WallStreamFilterNavigation::PANEL_POSITION_CENTER
            );
        
            // Get pages
            $websites = Website::find()
                ->where(['space_id' => $space['id']])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            $sortOrder = 0;
            foreach ($websites as $website) {
                $sortOrder++;

                // Add a filters to the new filter block
                $wallFilterNavigation->addFilter(
                    [
                        'id' => 'website_id_'.$website['id'],
                        'title' => Yii::t(
                            'ExternalWebsitesModule.models',
                            '{pageTitle}: show comments',
                            ['{pageTitle}' => $website['title']]
                        ),
                        'sortOrder' => $sortOrder,
                    ],
                    static::FILTER_BLOCK_EXTERNAL_WEBSITE
                );
            }
        }
    }


    // Adds filters
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
}
