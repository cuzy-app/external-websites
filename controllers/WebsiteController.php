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
use yii\helpers\BaseStringHelper;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\content\models\Content;
use humhub\modules\user\models\Group;


/**
 * When Humhub is host
 * Show external website pages in an iframe and pages contents addons beside
 */
class WebsiteController extends ContentContainerController
{
    public function actionIndex ($id)
    {
        // Get website from title
        $website = Website::findOne($id);
        if ($website === null) {
            throw new HttpException(404);
        }

        // Set first page URL
        $pageUrl = $website->first_page_url;

        // If pageId is in the URL
        $pageId = Yii::$app->request->get('pageId');
        if ($pageId !== null) {
            $page = Page::findOne($pageId);
            if ($page !== null) {
                $pageUrl = $page->page_url;
            }
        }
        // If pageUrl is in the URL (Page does not exist)
        else {
            $pageUrl = Yii::$app->request->get('pageUrl', $pageUrl);
        }

        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'website' => $website,
            'pageUrl' => $pageUrl,
        ]);
    }
}