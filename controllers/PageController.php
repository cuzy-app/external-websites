<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\stream\actions\ContentContainerStream;
use Yii;
use yii\base\Exception;
use yii\helpers\BaseStringHelper;
use yii\web\HttpException;


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
     * @throws Exception
     */
    public function actionIndex($id = null, $websiteId = null)
    {
        // Get elements to show
        $showComments = (bool)Yii::$app->request->post('showComments', Yii::$app->request->get('showComments', true));
        $showLikes = (bool)Yii::$app->request->post('showLikes', Yii::$app->request->get('showLikes', true));
        $showPermalink = (bool)Yii::$app->request->post('showPermalink', Yii::$app->request->get('showPermalink', true));

        // If page exists AND called from URL
        if ($id !== null && ($page = Page::findOne($id))) {
            $website = $page->website;
            $title = $page->title;
            $pageUrl = $page->page_url;
        } // If page doesn't exist (not comment) OR not called from URL
        else {
            // Get website (could be retrieve with $page->website, but as some pages may be shared with several websites, we need to specify the website desired)
            $website = Website::findOne($websiteId);
            if ($website === null) {
                throw new HttpException(404);
            }

            // Get page URL
            $pageUrl = Yii::$app->request->post('pageUrl', Yii::$app->request->get('pageUrl'));
            if (empty($pageUrl)) {
                throw new HttpException('403', 'Invalid param pageUrl!');
            }
            // Remove anchor (#hash) from URL and / at the end
            $pageUrl = rtrim(strtok($pageUrl, "#"), "/");
            // Remove params to ignore
            foreach ($website->getPageUrlParamsToRemove() as $param) {
                $pageUrl = Page::stripParamFromUrl($pageUrl, $param);
            }

            // Get title
            $pageTitle = Yii::$app->request->post('pageTitle', Yii::$app->request->get('pageTitle', ''));
            $pageTitle = str_ireplace($website->remove_from_url_title, '', $pageTitle); // Remove unwanted text in title
            $title = BaseStringHelper::truncate($pageTitle, 95, '[...]');

            // Get content (there can be only 1 unique URL per space, so we don't filter by website)
            $page = Page::find()
                ->contentContainer($this->contentContainer) // restrict to current space
                ->where(['page_url' => $pageUrl])
                ->one();

            if ($page !== null) {
                // If title has changed, update it
                if ($page->title !== $title) {
                    $page->title = $title;
                    $page->save();
                }

                // If related website is different (case where same URL is accessible form different websites in the same space)
                if ($website->id !== $page->website_id) {
                    // Make this page related to smaller sort order website (the first one in the space menu list)
                    if ($website->sort_order < $page->website->sort_order) {
                        $page->website_id = $website->id;
                        $page->save();
                    } else {
                        $otherWebsiteIds = $page->getOtherWebsiteIds();
                        if (!in_array($website->id, $otherWebsiteIds, true)) {
                            $page->setOtherWebsiteIds(array_merge($otherWebsiteIds, [$website->id]));
                            $page->save();
                        }
                    }
                }
            }
        }

        // Create permalink
        if (!$website->humhub_is_embedded) {
            if ($page !== null) {
                $permalink = $page->getUrl();
            } else {
                $permalink = $this->contentContainer->createUrl(
                    '/external-websites/website',
                    [
                        'id' => $website->id,
                        'pageUrl' => $pageUrl,
                    ],
                    true
                );
            }
        } else {
            $permalink = $pageUrl;
        }

        // If content archived and no comments, show only permalink
        if (
            $page !== null
            && $page->content->archived
            && Comment::GetCommentCount(Page::class, $page->id) === 0
        ) {
            $showComments = false;
            $showLikes = false;
        }

        // Create view params
        $viewParams = [
            'contentContainer' => $this->contentContainer,
            'website' => $website,
            'page' => $page,
            'pageUrl' => $pageUrl,
            'title' => $title,
            'permalink' => $permalink,
            'showComments' => $showComments,
            'showLikes' => $showLikes,
            'showPermalink' => $showPermalink,
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
}