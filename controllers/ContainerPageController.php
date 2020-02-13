<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\controllers;

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\announcements\components\StreamAction;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;


/**
 * Class BaseController
 * @package humhub\modules\wiki\controllers
 */
class ContainerPageController extends ContentContainerController
{

    /**
     * @inheritdoc
     */
    public $hideSidebar = true;

    public function actions()
    {
        return [
            'stream' => [
                'class' => StreamAction::class,
                'includes' => ContainerUrl::class,
                'mode' => StreamAction::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }


    public function actionIndex ()
    {
        if (!isset($_GET['title'])) {
            $this->redirect('index');
        }
        $title = urldecode($_GET['title']);

        $containerPage = ContainerPage::findOne([
            'title' => $title,
        ]);

        return $this->render('index', [
            'containerPage' => $containerPage,
        ]);
    }

}