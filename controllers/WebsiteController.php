<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\externalWebsites\models\Website;
use yii\web\HttpException;
use yii\web\Response;


/**
 * When Humhub is host (if embedded, redirects to external website)
 * Show external website pages in an iframe and pages content addons besides
 * @param $id Website ID
 * @param $title Website title
 * @param $pageId Page ID
 * @param $pageUrl string Page URL
 */
class WebsiteController extends ContentContainerController
{
    /**
     * @var Website used by the layout
     */
    public $website;

    /**
     * @param null $id
     * @param null $title
     * @param null $pageId
     * @param null $pageUrl
     * @return string|\yii\console\Response|Response
     * @throws HttpException
     */
    public function actionIndex($id = null, $title = null, $pageId = null, $pageUrl = null)
    {
        // Get website
        $website = null;
        if ($id !== null) {
            $website = Website::findOne($id);
        } // Try to get website from site title
        elseif ($title !== null) {
            $website = Website::findOne(['title' => $title]);
        }
        if ($website === null) {
            throw new HttpException(404, 'Website not found');
        }

        if ($website->layout === Website::LAYOUT_FULL_SCREEN) {
            $this->website = $website;
            $this->subLayout = '@external-websites/views/layouts/spaceFullScreenLayout';
        }

        // If $pageId not null and Page exists, set pageUrl from page
        if ($pageId !== null) {
            $page = Page::findOne($pageId);
            if ($page !== null) {
                $pageUrl = $page->page_url;
            }
        } // If pageUrl is null
        elseif (!$pageUrl) {
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
