<?php
/**
 * @var \humhub\modules\ui\view\components\View $this
 * @var \humhub\modules\content\components\ContentContainerController $context
 * @var string $content
 */

$context = $this->context;
$space = $context->contentContainer;
?>

<?= $content ?>

<script type="application/javascript">
    $('html.humhub-is-embedded').on('click', 'a[href^="/u/"]', function(e){
        e.preventDefault();
    });
</script>
