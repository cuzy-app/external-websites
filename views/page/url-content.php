<?php
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\like\widgets\LikeLink;
// use humhub\modules\comment\widgets\Comments; // Doesnt work with ajax
use humhub\modules\comment\widgets\Comment;
use humhub\modules\iframe\models\ContainerUrl;

$permalink = Url::to([
        '/s/'.urlencode($space['url']).'/iframe/page',
        'title' => urlencode($containerUrl->containerPage['title']),
        'urlId' => $containerUrl['id'],
    ], true);
?>

<div id="url-content" class="comment-state-<?= strtolower($commentsState) ?>">

    <div class="col-sm-12 colorFont5">
        <?php if (
            $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
            || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
        ): ?>
            <?= LikeLink::widget(['object' => $containerUrl]); ?><br>
        <?php endif; ?>
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
    </div>

    <?php if (
        $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
        || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
    ): ?>
        <div class="col-sm-12 comments">
            <?= \humhub\modules\comment\widgets\Comments::widget(['object' => $containerUrl]) ?>
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
    var hideSidebar = '<?= $containerUrl->containerPage['hide_sidebar'] ?>';
</script>