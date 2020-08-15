<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
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


    public function actionIndex ()
    {
        if (!isset($_GET['title'])) {
            $this->redirect('index');
        }
        $title = urldecode($_GET['title']);

        $containerPage = ContainerPage::findOne([
            'space_id' => $this->contentContainer['id'],
            'title' => $title,
        ]);

        // Set start URL
        $url = $containerPage['start_url'];
        if (isset($_GET['urlId'])) {
            $containerUrl = ContainerUrl::findOne(['id' => $_GET['urlId']]);
            $url = $containerUrl['url'];
        }

        return $this->render('index', [
            'containerPage' => $containerPage,
            'url' => $url,
        ]);
    }


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
        $url = rtrim(strtok($iframeMessage['url'], "#"),"/"); // remove anchor (#hash) from URL and / at the end
        $title = BaseStringHelper::truncate($iframeMessage['title'], 100, '[...]');

        // Get container page
        $containerPage = ContainerPage::findOne(['id' => $containerPageId]);

        // Remove unwanted text in title
        $title = str_ireplace($containerPage['remove_from_url_title'], '', $title);

        // Get content (there can be only 1 unique URL per space)
        $containerUrl = ContainerUrl::find()
            ->contentContainer($this->contentContainer) // restrict to current space
            ->where(['iframe_container_url.url' => $url])
            ->one();

        // if content does not exists, create it
        if ($containerUrl === null) {
            $containerUrl = new ContainerUrl($this->contentContainer);
            $containerUrl['container_page_id'] = $containerPageId;
            $containerUrl['url'] = $url;
            $containerUrl['title'] = $title;
            $containerUrl['hide_in_stream'] = $containerPage['default_hide_in_stream'];
            $containerUrl->content['visibility'] = $containerPage['visibility'];
            $containerUrl->content['archived'] = $containerPage['archived'];
            $containerUrl->save();
        }
        // If title has changed, update it
        elseif ($containerUrl['title'] != $title) {
            $containerUrl['title'] = $title;
            $containerUrl->save();
        }
        // If related container page is different (case where same URL is accessible form differents containers pages)
        elseif ($containerPage['id'] != $containerUrl['container_page_id']) {
            // make this container URL related to smaller sort order container page (the first one in the space menu list)
            if ($containerPage['sort_order'] < $containerUrl->containerPage['sort_order']) {
                $containerUrl['container_page_id'] = $containerPage['id'];
                $containerUrl->save();
            }
        }

        // Render ajax
        return $this->renderAjax('url-content', [
            'space' => $this->contentContainer,
            'containerUrl' => $containerUrl,
        ]);
    }
}