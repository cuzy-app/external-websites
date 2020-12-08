<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var $contentContainer humhub\modules\Space\models\Space
 * @var $pageUrl string url
 * @var $website humhub\modules\externalWebsites\models\Website
 */

humhub\modules\externalWebsites\assets\HostAssets::register($this);
$this->registerJsConfig('externalWebsites.Host', [
    'pageActionUrl' => $contentContainer->createUrl('page/index', ['websiteId' => $website->id])
]);
?>

<div id="ew-website" class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9 layout-content-container" id="ew-page-iframed">
                <iframe id="ew-page-container" src="<?= $pageUrl ?>" onload="humhub.modules.externalWebsites.Host.loadIFrameResizer()" allowfullscreen></iframe>
            </div>
            <div class="col-md-3 layout-sidebar-container" id="ew-page-addons">
            </div>
        </div>
    </div>
</div>