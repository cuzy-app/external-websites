<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
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
        <div class="col-lg-9 col-md-9 col-sm-9">
            <iframe id="iframe-container" src="<?= $iframeUrl ?>" onload="loadIFrameResizer()" allowfullscreen></iframe>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div id="iframe-comments"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // For module.js
    var urlContentActionUrl = '<?= Url::to('page/url-content') ?>';
</script>