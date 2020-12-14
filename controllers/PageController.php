<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\BaseStringHelper;
use Firebase\JWT\JWT;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\content\models\Content;
use humhub\modules\user\models\Group;
use humhub\modules\comment\models\Comment;


/**
 * Called by ajax (if Humhub is host) or iframe (if Humhub is embedded)
 */
class PageController extends ContentContainerController
{
    /**
     * @var humhub\modules\externalWebsites\models\Website
     */
    public $website;

    /**
     * @var humhub\modules\externalWebsites\models\Page
     */
    public $page;


    /**
     * @inheritDoc
     * If Humhub is embedded, the user may not be still logged in
     */
    public function beforeAction($action)
    {
        // Get Website
        $id = Yii::$app->request->get('id');
        $websiteId = Yii::$app->request->get('websiteId');

        // If page exists and called from URL
        if ($id !== null) {
            $this->page = Page::findOne($id);
            $this->website = $this->page->website;
        }
        else {
            // Get website (could be retreive with $page->website, but as some pages may be shared with several websites, we need to specify the website desired)
            $this->website = Website::findOne($websiteId);
            if ($this->website === null) {
                throw new HttpException(404);
            }
        }

        // If Humhub is embedded
        if ($this->website->humhub_is_embedded) {

            // Check if website making the request is authorized
            if (!$this->checkPermissionWithJwt()) {
                throw new ForbiddenHttpException();
            }

            if (Yii::$app->user->isGuest) {

                // Try auto login
                if (Yii::$app->request->get('autoLogin')) {
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

                $space = $this->contentContainer;

                // Auto add user to space if not member
                if (
                    Yii::$app->request->get('addToSpaceMembers')
                    && !$space->isMember(Yii::$app->user->id)
                ) {
                    $space->addMember(Yii::$app->user->id);
                }

                // Auto add group related to space to user if not member
                if (Yii::$app->request->get('addGroupRelatedToSpace')) {
                    // Get group related to space
                    $group = Group::findOne(['space_id' => $space->id]);
                    if ($group !== null) {
                        if (!$group->isMember(Yii::$app->user->identity)) {
                            $group->addUser(Yii::$app->user->identity);
                        }
                    }
                }
            }
        }

        return parent::beforeAction($action);
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
    public function actionIndex () {

        // If page exists and called from URL
        if ($this->page !== null) {
            $title = $this->page->title;
            $pageUrl = $this->page->page_url;
        }
        else {
            // Get page URL
            $pageUrl = Yii::$app->request->post('pageUrl', Yii::$app->request->get('pageUrl'));
            if (empty($pageUrl)) {
                throw new HttpException('403', 'Invalid param pageUrl!');
            }
            $pageUrl = rtrim(strtok($pageUrl, "#"),"/"); // remove anchor (#hash) from URL and / at the end

            // Get title
            $pageTitle = Yii::$app->request->post('pageTitle', Yii::$app->request->get('pageTitle', ''));
            $pageTitle = str_ireplace($this->website->remove_from_url_title, '', $pageTitle); // Remove unwanted text in title
            $title = BaseStringHelper::truncate($pageTitle, 100, '[...]');

            // Get content (there can be only 1 unique URL per space, so we don't filter by website)
            $this->page = Page::find()
                ->contentContainer($this->contentContainer) // restrict to current space
                ->where(['page_url' => $pageUrl])
                ->one();

            if ($this->page !== null) {
                // If title has changed, update it
                if ($this->page->title != $title) {
                    $this->page->title = $title;
                    $this->page->save();
                }
                
                // If related website is different (case where same URL is accessible form differents websites in the same space)
                if ($this->website->id != $this->page->website_id) {
                    // Make this page related to smaller sort order website (the first one in the space menu list)
                    if ($this->website->sort_order < $this->page->website->sort_order) {
                        $this->page->website_id = $this->website->id;
                        $this->page->save();
                    }
                }
            }
        }

        // Create permalink
        if (!$this->website->humhub_is_embedded) {
            if ($this->page !== null) {
                $permalink = $this->page->url;
            }
            else {
                $permalink = $this->contentContainer->createUrl(
                    '/external-websites/website',
                    [
                        'id' => $this->website->id,
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
            $this->page !== null
            && $this->page->content->archived
            && Comment::GetCommentCount(Page::class, $this->page->id) == 0
        ) {
            $showOnlyPermalink = true;
        }

        // Create view params
        $viewParams = [
            'contentContainer' => $this->contentContainer,
            'website' => $this->website,
            'page' => $this->page,
            'pageUrl' => $pageUrl,
            'title' => $title,
            'permalink' => $permalink,
            'showOnlyPermalink' => $showOnlyPermalink,
            'humhubIsEmbedded' => $this->website->humhub_is_embedded,
        ];

        // Render for ajax (Humhub is host)
        if (!$this->website->humhub_is_embedded) {
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
                if ($validData->website_id == $this->website->id) {
                    return true;
                }
            }

        } catch (Exception $e) {
            throw new HttpException(401, $e->getMessage());
        }

        return false;
    }
}