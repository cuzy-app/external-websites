<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

use yii\helpers\Url;
use yii\helpers\Html;

humhub\modules\iframe\assets\Assets::register($this);
?>

<div id="iframe-page" class="panel panel-default" data-container-page-id="<?= $containerPage['id'] ?>">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 iframe-content">
                <iframe src="<?= $containerPage['start_url'] ?>"></iframe>
            </div>
        </div>
        <div class="row">
            <div id="iframe-comments"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var urlContentActionUrl = '<?= Url::to('page/url-content') ?>';
</script>