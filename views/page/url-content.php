<?php
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\iframe\models\ContainerUrl;

$permalink = Url::to([
    '/s/'.urlencode($space['url']).'/iframe/page',
    'title' => urlencode($containerUrl->containerPage['title']),
    'urlId' => $containerUrl['id'],
], true);
?>

<div id="url-content">

    <div class="col-sm-12 colorFont5">
        <div class="wall-entry-controls">
            <?= Html::a(
                ' '.Yii::t('IframeModule.base', 'Permalink'),
                '#',
                [
                    'class' => 'permalink',
                    'data' => [
                        'action-click' => 'content.permalink',
                        'content-permalink' => $permalink,
                    ]
                ]
            ) ?>

            &middot; <?= LikeLink::widget(['object' => $containerUrl]); ?>
            &middot; <i class="fa fa-comment"></i> <?= CommentLink::widget(['object' => $containerUrl]); ?>
        </div>
    </div>

    <div class="col-sm-12 comments">
        <?= Comments::widget(['object' => $containerUrl]) ?>
    </div>

</div>

<script type="text/javascript">
    // Update browser URL
    window.history.replaceState({},'', '<?= $permalink ?>');

    // For module.js
    <?php if ($containerUrl->containerPage['hide_sidebar']) : ?>
        var hideSidebar = true;
    <?php else : ?>
        var hideSidebar = false;
    <?php endif; ?>
</script>