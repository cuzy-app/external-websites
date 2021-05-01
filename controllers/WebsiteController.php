<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use yii\web\HttpException;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;


/**
 * When Humhub is host (if embedded, redirects to external website)
 * Show external website pages in an iframe and pages contents addons beside
 * @param $id Website ID
 * @param $pageId Page ID
 * @param $pageUrl string Page URL
 */
class WebsiteController extends ContentContainerController
{
    public function actionIndex ($id, $pageId = null, $pageUrl = null)
    {
        // Get website
        $website = Website::findOne($id);
        if ($website === null) {
            throw new HttpException(404);
        }

        // If $pageId not null and Page exists, set pageUrl from page
        if ($pageId !== null) {
            $page = Page::findOne($pageId);
            if ($page !== null) {
                $pageUrl = $page->page_url;
            }
        }
        // If pageUrl is null
        elseif (empty($pageUrl)) {
            // Set first page URL
            $pageUrl = $website->first_page_url;
        }

        // If Humhub is embedded, redirect to external website
        if ($website->humhub_is_embedded) {
            return $this->redirect($pageUrl);
        }

        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'website' => $website,
            'pageUrl' => $pageUrl,
        ]);
    }
}