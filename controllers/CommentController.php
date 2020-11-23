<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\controllers;

use Yii;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Space;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;

/**
 * CommentController enables to create the content if not exists
 */
class CommentController extends \humhub\modules\comment\controllers\CommentController
{

    /**
     * @inheritDoc
     * Overwrites beforeAction() of the parent class
     */
    public function beforeAction($action)
    {
        $this->module = Yii::$app->getModule('comment');

        $iframeUrl = Yii::$app->request->post('iframeUrl');
        $iframeTitle = Yii::$app->request->post('iframeTitle', '');
        $containerPageId = (int)Yii::$app->request->post('containerPageId');

        if (empty($iframeUrl) || empty($containerPageId)) {
            throw new HttpException(500, 'Error in the comment form!');
        }

        // Get container page
        $containerPage = ContainerPage::findOne($containerPageId);

        // Get space
        $space = Space::findOne($containerPage['space_id']);

        // Check if not exists (if someone else has commented after form has been loaded)
        $containerUrl = ContainerUrl::find()
            ->contentContainer($space) // restrict to current space
            ->where(['url' => $iframeUrl])
            ->one();

        if ($containerUrl === null) {
            $containerUrl = new ContainerUrl($space);
            $containerUrl['container_page_id'] = $containerPageId;
            $containerUrl['url'] = $iframeUrl;
            $containerUrl['title'] = $iframeTitle;
            $containerUrl->content['visibility'] = $containerPage['visibility'];
            $containerUrl->content['archived'] = $containerPage['archived'];
            $containerUrl->save();
        }

        $this->target = $containerUrl;
        $this->content = $this->target->content;

        return Controller::beforeAction($action);
    }
}