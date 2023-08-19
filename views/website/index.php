<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\modules\externalWebsites\assets\HostAssets;
use humhub\modules\externalWebsites\models\Website;

/**
 * @var $contentContainer humhub\modules\space\models\Space
 * @var $pageUrl string url
 * @var $website humhub\modules\externalWebsites\models\Website
 */

HostAssets::register($this);
$this->registerJsConfig('externalWebsites.Host', [
    'pageActionUrl' => $contentContainer->createUrl('page/index', ['websiteId' => $website->id]),
    'hideSidebar' => $website->layout === Website::LAYOUT_MENU_COLLAPSED,
]);
?>

<div id="ew-website" class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div id="ew-page-iframed" class="col-md-12">
                <iframe id="ew-page-container" src="<?= $pageUrl ?>" allowfullscreen></iframe>
                <br><br>
            </div>
            <div id="ew-page-addons" class="col-md-12">
            </div>
        </div>
    </div>
</div>