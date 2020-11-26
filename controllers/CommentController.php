<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use Yii;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Space;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;


/**
 * CommentController enables to create the content if not exists
 * Called by form in `external-websites\widgets\views\firestCommentForm.php`
 * For a page not created because not having any comment yet
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

        $pageUrl = Yii::$app->request->post('pageUrl');
        $pageTitle = Yii::$app->request->post('pageTitle', '');
        $websiteId = (int)Yii::$app->request->post('websiteId');

        if (empty($pageUrl) || empty($websiteId)) {
            throw new HttpException(500, 'Error in the comment form!');
        }

        // Get website
        $website = Website::findOne($websiteId);

        // Get space
        $space = Space::findOne($website['space_id']);

        // Check if not exists (if someone else has commented after form has been loaded)
        $page = Page::find()
            ->contentContainer($space) // restrict to current space
            ->where(['url' => $pageUrl])
            ->one();

        if ($page === null) {
            $page = new Page($space);
            $page['website_id'] = $websiteId;
            $page['url'] = $pageUrl;
            $page['title'] = $pageTitle;
            $page->content['visibility'] = $website['default_content_visibility'];
            $page->content['archived'] = $website['default_content_archived'];
            $page->save();
        }

        $this->target = $page;
        // $this->content = $this->target->content;

        return Controller::beforeAction($action);
    }
}