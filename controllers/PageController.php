<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\controllers;

use Yii;
use yii\helpers\BaseStringHelper;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;
use humhub\modules\content\models\Content;


class PageController extends \humhub\modules\content\components\ContentContainerController
{

    const MAX_COMMENTS = 20;

    /**
     * @inheritdoc
     */
    public $hideSidebar = true;

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

        // Set start URL
        $iframeUrl = $containerPage['start_url'];

        // If urlId is in the URL
        if (($urlId = Yii::$app->request->get('urlId')) !== null) {
            $containerUrl = ContainerUrl::findOne(['id' => $_GET['urlId']]);
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
     * Called by ajax
     */
    public function actionUrlContent () {
        // Get iframe URL
        if (
            !isset($_POST['containerPageId'])
            || !isset($_POST['iframeMessage'])
        ) {
            return;
        }
        $containerPageId = $_POST['containerPageId'];
        $iframeMessage = $_POST['iframeMessage'];
        $iframeUrl = rtrim(strtok($iframeMessage['url'], "#"),"/"); // remove anchor (#hash) from URL and / at the end
        $iframeTitle = BaseStringHelper::truncate($iframeMessage['title'], 100, '[...]');

        // Get container page
        $containerPage = ContainerPage::findOne(['id' => $containerPageId]);

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

        // Render ajax
        return $this->renderAjax('url-content', [
            'space' => $this->contentContainer,
            'containerPage' => $containerPage,
            'containerUrl' => $containerUrl,
            'iframeUrl' => $iframeUrl,
            'iframeTitle' => $iframeTitle,
        ]);
    }
}