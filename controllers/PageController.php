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
use humhub\modules\content\models\Content;


/**
 * Class BaseController
 * @package humhub\modules\wiki\controllers
 */
class PageController extends ContentContainerController
{

    const MAX_COMMENTS = 1;

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


    public function actionUrlContent () {

        // Get iframe URL
        if (
            !isset($_POST['containerPageId'])
            || !isset($_POST['url'])
        ) {
            return;
        }
        $containerPageId = $_POST['containerPageId'];
        $url = $_POST['url'];

        // Get content
        $containerUrl = ContainerUrl::findOne([
            'container_page_id' => $containerPageId,
            'url' => $url,
        ]);

        // if content does not exists, create it
        if ($containerUrl === null) {
            $containerPage = ContainerPage::findOne([
                'id' => $containerPageId,
            ]);
            $containerUrl = new ContainerUrl();
            $containerUrl['container_page_id'] = $containerPageId;
            $containerUrl['url'] = $url;
            $containerUrl['comments_state'] = $containerPage['comments_global_state'];
            $containerUrl->content->container = $this->space;
            $containerUrl->content->visibility = $containerPage['visibility'];
            $containerUrl->save();
        }

        // Get comments because humhub\modules\comment\widgets\Comments doesn't work with ajax
        $objectModel = $containerUrl->content->object_model;
        $objectId = $containerUrl->content->object_id;

        $commentsCount = \humhub\modules\comment\models\Comment::GetCommentCount($objectModel, $objectId);
        $comments = \humhub\modules\comment\models\Comment::GetCommentsLimited($objectModel, $objectId, self::MAX_COMMENTS);

        $isLimitedComments = ($commentsCount > self::MAX_COMMENTS);

        return $this->renderAjax('url-content', [
            'containerUrl' => $containerUrl,
            'comments' => $comments,
            'objectModel' => $objectModel,
            'objectId' => $objectId,
            'isLimitedComments' => $isLimitedComments,
            'commentsCount' => $commentsCount,
        ]);
    }

}