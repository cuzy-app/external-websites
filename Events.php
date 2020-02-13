<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe;

use Yii;
use humhub\modules\iframe\models\ContainerPage;


class Events
{

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
            $containerPage = ContainerPage::findAll([
                'space_id' => $space['id'],
            ]);

            foreach ($containerPage as $containerPage) {
                $event->sender->addItem([
                    'label' => $containerPage['title'],
                    'group' => 'modules',
                    'url' => $space->createUrl('/iframe/container-page?title='.urlencode($containerPage['title'])),
                    'icon' => '<i class="fa '.$containerPage['icon'].'"></i>',
                    'isActive' => (
                        Yii::$app->controller->module
                        && Yii::$app->controller->module->id == 'iframe'
                        && Yii::$app->controller->id = 'container-page'
                        && $currentPageTitle !== null
                        && $currentPageTitle == $containerPage['title']
                    ),
                ]);
            }
        }
    }
}
