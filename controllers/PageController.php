<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\BaseStringHelper;
use Firebase\JWT\JWT;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\comment\models\Comment;


/**
 * Called by ajax (if Humhub is host) or iframe (if Humhub is embedded)
 */
class PageController extends ContentContainerController
{
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
     * Called by ajax (if Humhub is host) or iframe (if Humhub is embedded)
     * If Humhub is embedded, see README.md for complete URL to provide in the iframe scr
     * All params are optional, but we need a least either $id or ($websiteId and $pageUrl)
     * @param $id Page ID (1)
     * @param $websiteId Website ID (1)
     * @param $pageUrl Page page_url
     * @param $pageTitle Page title
     * @param $autoLogin boolean (1)
     * @param $token string HS512 JWT token (1)
     * (1) used by beforeAction function
     */
    public function actionIndex ($id = null, $websiteId = null) {

        // If page exists AND called from URL
        if ($id !== null) {
            $page = Page::findOne($id);
            $website = $page->website;
            $title = $page->title;
            $pageUrl = $page->page_url;
        }

        // If page doesn't exists (not comment) OR not called from URL
        else {
            // Get website (could be retreive with $page->website, but as some pages may be shared with several websites, we need to specify the website desired)
            $website = Website::findOne($websiteId);
            if ($website === null) {
                throw new HttpException(404);
            }

            // Get page URL
            $pageUrl = Yii::$app->request->post('pageUrl', Yii::$app->request->get('pageUrl'));
            if (empty($pageUrl)) {
                throw new HttpException('403', 'Invalid param pageUrl!');
            }
            $pageUrl = rtrim(strtok($pageUrl, "#"),"/"); // remove anchor (#hash) from URL and / at the end

            // Get title
            $pageTitle = Yii::$app->request->post('pageTitle', Yii::$app->request->get('pageTitle', ''));
            $pageTitle = str_ireplace($website->remove_from_url_title, '', $pageTitle); // Remove unwanted text in title
            $title = BaseStringHelper::truncate($pageTitle, 100, '[...]');

            // Get content (there can be only 1 unique URL per space, so we don't filter by website)
            $page = Page::find()
                ->contentContainer($this->contentContainer) // restrict to current space
                ->where(['page_url' => $pageUrl])
                ->one();

            if ($page !== null) {
                // If title has changed, update it
                if ($page->title != $title) {
                    $page->title = $title;
                    $page->save();
                }
                
                // If related website is different (case where same URL is accessible form differents websites in the same space)
                if ($website->id != $page->website_id) {
                    // Make this page related to smaller sort order website (the first one in the space menu list)
                    if ($website->sort_order < $page->website->sort_order) {
                        $page->website_id = $website->id;
                        $page->save();
                    }
                }
            }
        }

        // Create permalink
        if (!$website->humhub_is_embedded) {
            if ($page !== null) {
                $permalink = $page->url;
            }
            else {
                $permalink = $this->contentContainer->createUrl(
                    '/external-websites/website',
                    [
                        'id' => $website->id,
                        'pageUrl' => $pageUrl,
                    ],
                    true
                );
            }
        }
        else {
            $permalink = $pageUrl;
        }

        // If content archived and no comments, show only permalink
        $showOnlyPermalink = false;
        if (
            $page !== null
            && $page->content->archived
            && Comment::GetCommentCount(Page::class, $page->id) == 0
        ) {
            $showOnlyPermalink = true;
        }

        // Create view params
        $viewParams = [
            'contentContainer' => $this->contentContainer,
            'website' => $website,
            'page' => $page,
            'pageUrl' => $pageUrl,
            'title' => $title,
            'permalink' => $permalink,
            'showOnlyPermalink' => $showOnlyPermalink,
            'humhubIsEmbedded' => $website->humhub_is_embedded,
        ];

        // Render for ajax (Humhub is host)
        if (!$website->humhub_is_embedded) {
            return $this->renderAjax('index', $viewParams);
        }

        // Render for iframe (Humhub is embedded)
        $this->layout = '@external-websites/views/layouts/iframe';
        $this->subLayout = '@external-websites/views/page/_layoutForIframe';
        return $this->render('index', $viewParams);
    }


    /**
     * Check permission using JWT Bearer Header
     *
     * @return boolean
     * @throws HttpException
     */
    private function checkPermissionWithJwt()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('external-websites');

        // If no JWT key in the configuration, do not check permission with JWT
        if (empty($module->jwtKey)) {
            return true;
        }

        $token = Yii::$app->request->get('token');

        try {
            $validData = JWT::decode($token, $module->jwtKey, ['HS512']);

            if (!empty($validData->website_id)) {
                if ($validData->website_id == $website->id) {
                    return true;
                }
            }

        } catch (Exception $e) {
            throw new HttpException(401, $e->getMessage());
        }

        return false;
    }
}