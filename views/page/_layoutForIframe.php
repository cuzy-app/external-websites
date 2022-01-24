<?php
/**
 * @var View $this
 * @var ContentContainerController $context
 * @var string $content
 */

use humhub\libs\Html;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\ui\view\components\View;

$context = $this->context;
$space = $context->contentContainer;
?>

<?= $content ?>

<script <?= Html::nonce() ?>>
    $('html.humhub-is-embedded').on('click', 'a[href^="/u/"]', function (e) {
        e.preventDefault();
    });
</script>
