<?php

use humhub\helpers\Html;

?>
<script <?= Html::nonce() ?>>
    // Add a class to html tag to know if HumHub is in an iframe or not
    if (window.self !== window.top) {
        $('html').addClass('humhub-is-embedded');
    } else {
        $('html').addClass('humhub-is-not-embedded');
    }
</script>
