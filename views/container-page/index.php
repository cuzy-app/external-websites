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

<div id="iframe-container-page" class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 iframe-content">
                <iframe src="<?= $containerPage['start_url'] ?>"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    // loads when DOM is ready, even if coming from pjax
    $(document).on('humhub:ready', function() {
        iFrameResize(
            {
                log: false,
                scrolling: true, // if iframed website has not the content window javascript
                inPageLinks: true,

                // Each time iframed website has loaded the content window javascript
                onInit: function(messageData) {
                    // Remove scrollbar
                    $(this).attr('scrolling', 'no');
                },

                // Each time iframed page is loaded or URL changes
                onMessage: function(messageData) {
                    iframeUrl = messageData.message;

                },
            },
            '#iframe-container-page iframe'
        );
    });
</script>