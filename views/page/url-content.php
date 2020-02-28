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

<div class="col-sm-12 colorFont5">
    <?php if (
        $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
        || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
    ): ?>
        <?= LikeLink::widget(['object' => $containerUrl]); ?> | 
    <?php endif; ?>
    <?= Html::a(
        Yii::t('IframeModule.base', 'Page’s permalink'),
        '#',
        [
            'data' => [
                'action-click' => 'content.permalink',
                'content-permalink' => $permalink,
            ]
        ]
    ) ?>
    <hr>
</div>

<?php if (
    $commentsState == ContainerUrl::COMMENTS_STATE_ENABLED
    || $commentsState == ContainerUrl::COMMENTS_STATE_CLOSED
): ?>
    <div class="col-sm-12 comments">
    	<?php if ($isLimitedComments): ?>
    		<a href="#" class="show show-all-link" data-ui-loader data-action-click="comment.showAll" data-action-url="<?= Url::to(['/comment/comment/show', 'contentModel' => $objectModel, 'contentId' => $objectId]) ?>">
                <?= Yii::t('CommentModule.widgets_views_comments', 'Show all {total} comments.', ['{total}' => $commentsCount]) ?>
            </a>
        <?php endif; ?>

        <?php foreach ($comments as $comment) : ?>
            <?= Comment::widget(['comment' => $comment]); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($commentsState == ContainerUrl::COMMENTS_STATE_ENABLED): ?>
    <div class="col-sm-12 comment-link">
        <?= Html::a(
        	Yii::t('CommentModule.widgets_views_form', 'Write a new comment...'),
        	'#',
        	[
        		'id' => 'load-iframe-comments',
        		'class' => 'btn btn-default',
        		'data' => [
        			'action-url' => Url::to(['/comment/comment/show', 'contentModel' => $objectModel, 'contentId' => $objectId, 'mode' => 'popup']),
        			// next datas are not used because we need to reload ajax after modal box is closed ; This will be done with loadIframeComments() function in module.js
        			// 'action-click' => 'ui.modal.load',
        			// 'modal-id' => 'write-comment-modal',
        		],
        	]
        ) ?>
        <script type="text/javascript">
            $('#load-iframe-comments').on('click', function(e) {
                e.preventDefault();
                loadIframeComments($(this).attr('data-action-url')); // function in module.js
            }); 
        </script>
    </div>
<?php endif; ?>

<?php // Make "Like" read only ?>
<?php if ($commentsState == ContainerUrl::COMMENTS_STATE_CLOSED): ?>
    <style type="text/css">
        #iframe-page .likeLinkContainer a.likeAnchor {
            display: none;
        }
        #iframe-page .likeLinkContainer .likeCount::before {
            content: '<?= str_replace("'", "’", Yii::t('LikeModule.widgets_views_likeLink', 'Like')) ?> ';
        }
    </style>
<?php endif; ?>

<script type="text/javascript">
    window.history.pushState({},'', '<?= $permalink ?>');
</script>