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

<div id="url-content" class="comment-state-<?= strtolower($commentsState) ?>">

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

            <?php if (
                $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
                || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
            ): ?>
                &middot; <?= LikeLink::widget(['object' => $containerUrl]); ?>
                &middot; <?= CommentLink::widget(['object' => $containerUrl]); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (
        $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
        || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
    ): ?>
        <div class="col-sm-12 comments">
            <?= Comments::widget(['object' => $containerUrl]) ?>
        </div>
    <?php endif; ?>

</div>

<style type="text/css">
    #iframe-page #url-content.comment-state-closed .likeLinkContainer .likeCount::before {
        content: '<?= str_replace("'", "’", Yii::t('LikeModule.widgets_views_likeLink', 'Like')) ?> ';
    }
</style>

<script type="text/javascript">
    window.history.pushState({},'', '<?= $permalink ?>');

    // For module.js
    var hideSidebar = false;
    <?php if ($containerUrl->containerPage['hide_sidebar']) : ?>
        hideSidebar = true;
    <?php endif; ?>
</script>