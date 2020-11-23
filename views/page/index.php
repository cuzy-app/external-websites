<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var $iframeUrl string url
 * @var $containerPage humhub\modules\iframe\models\ContainerPage
 */

humhub\modules\iframe\assets\Assets::register($this);
?>

<div id="iframe-page" class="panel panel-default" data-container-page-id="<?= $containerPage['id'] ?>">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9 layout-content-container">
                <iframe id="iframe-container" src="<?= $iframeUrl ?>" onload="humhub.modules.iframe.loadIFrameResizer()" allowfullscreen></iframe>
            </div>
            <div class="col-md-3 layout-sidebar-container" id="iframe-comments">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // For humhub.iframe.js
    var urlContentActionUrl = '<?= Url::to('page/url-content') ?>';
</script>