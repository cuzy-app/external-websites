<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use Yii;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\filters\IframeStreamFilter;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;


class Events
{
    const FILTER_BLOCK_IFRAME = 'iframe';
    const FILTER_IFRAME = 'iframe';


    public static function onSpaceMenuInit($event)
    {
        // Get current page URL if exists
        $currentPageTitle = null;
        if (isset($_GET['title'])) {
            $currentPageTitle = urldecode($_GET['title']);
        }

        $space = $event->sender->space;

        if ($space !== null && $space->isModuleEnabled('iframe')) {

            // Get pages
            $containerPages = ContainerPage::find()
                ->where(['space_id' => $space['id']])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            foreach ($containerPages as $containerPage) {
                $event->sender->addItem([
                    'label' => $containerPage['title'],
                    'group' => 'modules',
                    'url' => $space->createUrl('/iframe/page?title='.urlencode($containerPage['title'])),
                    'icon' => '<i class="fa '.$containerPage['icon'].'"></i>',
                    'isActive' => (
                        Yii::$app->controller->module
                        && Yii::$app->controller->module->id == 'iframe'
                        && Yii::$app->controller->id = 'page'
                        && $currentPageTitle !== null
                        && $currentPageTitle == $containerPage['title']
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
        if ($space !== null && $space->isModuleEnabled('iframe')) {

            /** @var $wallFilterNavigation WallStreamFilterNavigation */
            $wallFilterNavigation = $event->sender;
        
            // Add a new filter block to the last filter panel
            $wallFilterNavigation->addFilterBlock(
                static::FILTER_BLOCK_IFRAME, [
                    'title' => Yii::t('IframeModule.model', 'Filter'),
                    'sortOrder' => 300
                ],
                WallStreamFilterNavigation::PANEL_POSITION_CENTER
            );
        
            // Get pages
            $containerPages = ContainerPage::find()
                ->where(['space_id' => $space['id']])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

            $sortOrder = 0;
            foreach ($containerPages as $containerPage) {
                $sortOrder++;

                // Add a filters to the new filter block
                $wallFilterNavigation->addFilter(
                    [
                        'id' => 'container_page_id_'.$containerPage['id'],
                        'title' => Yii::t(
                            'IframeModule.models',
                            '{pageTitle}: show comments',
                            ['{pageTitle}' => $containerPage['title']]
                        ),
                        'sortOrder' => $sortOrder,
                    ],
                    static::FILTER_BLOCK_IFRAME
                );
            }
        }
    }


    public static function onStreamFilterBeforeFilter ($event)
    {
        if (!isset(Yii::$app->controller->contentContainer)) {
            return;
        }
        $space = Yii::$app->controller->contentContainer;
        if ($space !== null && $space->isModuleEnabled('iframe')) {
            /** @var $streamQuery WallStreamQuery */
            $streamQuery = $event->sender;
        
            // Add a new filterHandler to WallStreamQuery
            $streamQuery->filterHandlers[] = IframeStreamFilter::class;
        }
    }
}
