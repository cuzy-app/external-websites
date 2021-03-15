<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use humhub\modules\externalWebsites\assets\HostAssets;

/**
 * @var $contentContainer humhub\modules\space\models\Space
 * @var $pageUrl string url
 * @var $website humhub\modules\externalWebsites\models\Website
 */

HostAssets::register($this);
$this->registerJsConfig('externalWebsites.Host', [
    'pageActionUrl' => $contentContainer->createUrl('page/index', ['websiteId' => $website->id])
]);
?>

<div id="ew-website" class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div id="ew-page-iframed" class="col-md-12">
            </div>
            <div id="ew-page-addons" class="col-md-12">
            </div>
        </div>
    </div>
</div>

<?php // Set iframe tag after HostAssets is completely loaded as it calls loadIFrameResizer function after loading ?>
<script type="text/javascript">
    $(function(){
        var pageUrl = <?= json_encode($pageUrl, JSON_HEX_TAG) ?>;
        $('#ew-page-iframed').append('<iframe id="ew-page-container" src="'+pageUrl+'" onload="humhub.modules.externalWebsites.Host.loadIFrameResizer()" allowfullscreen></iframe>');
    });
</script>