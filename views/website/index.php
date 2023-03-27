<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
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
                <br><br>
            </div>
            <div id="ew-page-addons" class="col-md-12">
            </div>
        </div>
    </div>
</div>

<?php // Set iframe tag after HostAssets is completely loaded as it calls loadIFrameResizer function after loading ?>
<script <?= Html::nonce() ?>>
    $(function () {
        var pageUrl = <?= json_encode($pageUrl, JSON_HEX_TAG) ?>;
        $('#ew-page-iframed').prepend('<iframe id="ew-page-container" src="' + pageUrl + '" onload="humhub.modules.externalWebsites.Host.loadIFrameResizer()" allowfullscreen></iframe>');
    });
</script>