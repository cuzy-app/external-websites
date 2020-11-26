<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\helpers\BaseStringHelper;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\content\models\Content;
use humhub\modules\user\models\Group;


/**
 * Called by ajax (if Humhub is host) or iframe (if Humhub is guest)
 */
class PageController extends \humhub\modules\content\components\ContentContainerController
{

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {

            // Try auto login
            if (Yii::$app->request->get('autoLogin') == true) {
                // If an auth client has attribute autoLogin set to true, this module will auto log the user to the corresponding Identity provider (SSO)
                foreach (Yii::$app->authClientCollection->clients as $authclient) {
                    if (isset($authclient->autoLogin) && $authclient->autoLogin) {
                        // Redirect to Identity Provider
                        if (method_exists($authclient, 'redirectToBroker')) {
                            return $authclient->redirectToBroker();
                        }
                    }
                }
            }
        }
        // If logged in
        else {

            // Auto add user to space if not member
            if (Yii::$app->request->get('addToSpaceMembers') == true) {
                $space = $this->getSpaceFromWebsiteId(Yii::$app->request->get('websiteId'));
                if (!$space->isMember(Yii::$app->user->id)) {
                    $space->addMember(Yii::$app->user->id);
                }
            }

            // Auto add group related to space to user if not member
            if (Yii::$app->request->get('addGroupRelatedToSpace') == true) {
                if (!isset($space)) {
                    $space = $this->getSpaceFromWebsiteId(Yii::$app->request->get('websiteId'));
                }
                // Get group related to space
                $group = Group::findOne(['space_id' => $space->id]);
                if ($group !== null) {
                    if (!$group->isMember(Yii::$app->user->identity)) {
                        $group->addUser(Yii::$app->user->identity);
                    }
                }
            }
        }

        return parent::beforeAction($action);
    }


    protected function getSpaceFromWebsiteId ($websiteId)
    {
        if ($websiteId !== null) {
            $website = Website::findOne($websiteId);
            if ($website !== null) {
                return $website->space;
            }
        }
        return null;
    }


    public function actions()
    {
        return [
            'stream' => [
                'class' => ContentContainerStream::class,
                'includes' => Page::class,
                'mode' => ContentContainerStream::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }


    /**
     * Called by ajax (if Humhub is host) or iframe (if Humhub is guest)
     * @param $humhubIsHost boolean
     */
    public function actionIndex ($humhubIsHost = true) {
        // Get website ID (POST if ajax, GET if iframe)
        $websiteId = Yii::$app->request->post('websiteId', Yii::$app->request->get('websiteId'));

        // Get page URL and Title (if from ajax, in $iframeMessage array)
        if ($humhubIsHost) {
            $iframeMessage = Yii::$app->request->post('iframeMessage');
            if (!empty($iframeMessage)) {
                $pageUrl = $iframeMessage['url'];
                $pageTitle = $iframeMessage['title'];
            }
        }
        else {
            $pageUrl = Yii::$app->request->get('url');
            $pageTitle = Yii::$app->request->get('title');
        }

        if (empty($websiteId) || empty($url)) {
            throw new HttpException('401', 'Invalid param!');
        }
        
        // Get website
        $website = Website::findOne($websiteId);
        if ($website === null) {
            throw new HttpException(404);
        }

        // Format page URL and Title
        $pageUrl = rtrim(strtok($pageUrl, "#"),"/"); // remove anchor (#hash) from URL and / at the end
        $pageTitle = str_ireplace($website['remove_from_url_title'], '', $pageTitle); // Remove unwanted text in title
        $pageTitle = BaseStringHelper::truncate($pageTitle, 100, '[...]');

        // Get content (there can be only 1 unique URL per space, so we don't filter by website)
        $page = Page::find()
            ->contentContainer($this->contentContainer) // restrict to current space
            ->where(['url' => $pageUrl])
            ->one();

        if ($page !== null) {
            // If title has changed, update it
            if ($page['title'] != $pageTitle) {
                $page['title'] = $pageTitle;
                $page->save();
            }
            // If related website is different (case where same URL is accessible form differents websites in the same space)
            if ($website['id'] != $page['website_id']) {
                // Make this page related to smaller sort order website (the first one in the space menu list)
                if ($website['sort_order'] < $page->website['sort_order']) {
                    $page['website_id'] = $website['id'];
                    $page->save();
                }
            }
        }

        // Create permalink
        $permalinkParams = [
            'title' => $website['title'],
        ];
        if ($page !== null) {
            $permalinkParams['pageId'] = $page['id'];
        }
        else {
            $permalinkParams['pageUrl'] = $pageUrl;
        }
        $permalink = $space->createUrl('/external-websites/page', $permalinkParams, true);

        // Create view params
        $viewParams = [
            'space' => $this->contentContainer,
            'website' => $website,
            'page' => $page,
            'pageUrl' => $pageUrl,
            'pageTitle' => $pageTitle,
            'permalink' => $permalink,
            'humhubIsHost' => $humhubIsHost,
        ];

        // Render for ajax (Humhub is host)
        if ($humhubIsHost) {
            return $this->renderAjax('index', $viewParams);
        }

        // Render for iframe (Humhub is guest)
        $this->layout = '@humhub/modules/external-websites/views/layouts/iframe';
        $this->subLayout = '@humhub/modules/external-websites/views/page/_layoutForIframe';
        return $this->render('index', $viewParams);
    }
}