<?php
/**
 * @var View $this
 * @var ContentContainerController $context
 * @var string $content
 */

use humhub\components\View;
use humhub\helpers\Html;
use humhub\modules\content\components\ContentContainerController;

$context = $this->context;
$space = $context->contentContainer;
?>

<?= $content ?>

<script <?= Html::nonce() ?>>
    $('html.humhub-is-embedded').on('click', 'a[href^="/u/"]', function (e) {
        e.preventDefault();
    });
</script>
