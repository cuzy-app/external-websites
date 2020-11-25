<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\helpers\BaseStringHelper;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;
use humhub\modules\content\models\Content;
use humhub\modules\user\models\Group;


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
                $space = $this->getSpaceFromContainerPageId(Yii::$app->request->get('containerPageId'));
                if (!$space->isMember(Yii::$app->user->id)) {
                    $space->addMember(Yii::$app->user->id);
                }
            }

            // Auto add group related to space to user if not member
            if (Yii::$app->request->get('addGroupRelatedToSpace') == true) {
                if (!isset($space)) {
                    $space = $this->getSpaceFromContainerPageId(Yii::$app->request->get('containerPageId'));
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


    protected function getSpaceFromContainerPageId ($containerPageId)
    {
        if ($containerPageId !== null) {
            $containerPage = ContainerPage::findOne($containerPageId);
            if ($containerPage !== null) {
                return $containerPage->space;
            }
        }
        return null;
    }


    public function actions()
    {
        return [
            'stream' => [
                'class' => ContentContainerStream::class,
                'includes' => ContainerUrl::class,
                'mode' => ContentContainerStream::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }


    public function actionIndex ($title)
    {
        $containerPage = ContainerPage::findOne([
            'space_id' => $this->contentContainer['id'],
            'title' => $title,
        ]);
        if ($containerPage === null) {
            throw new HttpException(404);
        }

        // Set start URL
        $iframeUrl = $containerPage['start_url'];

        // If urlId is in the URL
        $urlId = Yii::$app->request->get('urlId');
        if ($urlId !== null) {
            $containerUrl = ContainerUrl::findOne($urlId);
            if ($containerUrl !== null) {
                $iframeUrl = $containerUrl['url'];
            }
        }
        // If iframeUrl is in the URL (ContainerUrl does not exist)
        else {
            $iframeUrl = Yii::$app->request->get('iframeUrl', $iframeUrl);
        }

        return $this->render('index', [
            'containerPage' => $containerPage,
            'iframeUrl' => $iframeUrl,
        ]);
    }


    /**
     * Called by ajax or in an iframe
     */
    public function actionUrlContent ($iframe = false) {
        $containerPageId = Yii::$app->request->post('containerPageId', Yii::$app->request->get('containerPageId'));
        $iframeMessage = Yii::$app->request->post('iframeMessage', Yii::$app->request->get('iframeMessage'));
        if (!empty($iframeMessage)) {
            $url = $iframeMessage['url'];
            $title = $iframeMessage['title'];
        }
        else {
            $url = Yii::$app->request->get('url');
            $title = Yii::$app->request->get('title');
        }

        // Get iframe URL
        if (empty($containerPageId) || empty($url)) {
            throw new HttpException('401', 'Invalid param!');
        }
        
        $iframeUrl = rtrim(strtok($url, "#"),"/"); // remove anchor (#hash) from URL and / at the end
        $iframeTitle = BaseStringHelper::truncate($title, 100, '[...]');

        // Get container page
        $containerPage = ContainerPage::findOne($containerPageId);
        if ($containerPage === null) {
            throw new HttpException(404);
        }

        // Remove unwanted text in title
        $iframeTitle = str_ireplace($containerPage['remove_from_url_title'], '', $iframeTitle);

        // Get content (there can be only 1 unique URL per space, so we don't filter by container page)
        $containerUrl = ContainerUrl::find()
            ->contentContainer($this->contentContainer) // restrict to current space
            ->where(['url' => $iframeUrl])
            ->one();

        if ($containerUrl !== null) {
            // If title has changed, update it
            if ($containerUrl['title'] != $iframeTitle) {
                $containerUrl['title'] = $iframeTitle;
                $containerUrl->save();
            }
            // If related container page is different (case where same URL is accessible form differents containers pages in the same space)
            if ($containerPage['id'] != $containerUrl['container_page_id']) {
                // make this container URL related to smaller sort order container page (the first one in the space menu list)
                if ($containerPage['sort_order'] < $containerUrl->containerPage['sort_order']) {
                    $containerUrl['container_page_id'] = $containerPage['id'];
                    $containerUrl->save();
                }
            }
        }

        $viewParams = [
            'space' => $this->contentContainer,
            'containerPage' => $containerPage,
            'containerUrl' => $containerUrl,
            'iframeUrl' => $iframeUrl,
            'iframeTitle' => $iframeTitle,
        ];

        // Render for iframe
        if ($iframe) {
            $this->layout = '@humhub/modules/iframe/views/layouts/iframe';
            $this->subLayout = '@humhub/modules/iframe/views/page/_layoutForIframe';
            return $this->render('url-content', $viewParams);
        }

        // Render ajax
        return $this->renderAjax('url-content', $viewParams);
    }
}