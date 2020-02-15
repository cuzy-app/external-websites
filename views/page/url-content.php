<?php
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\like\widgets\LikeLink;
// use humhub\modules\comment\widgets\Comments; // Doesnt work with ajax
use humhub\modules\comment\widgets\Comment;
?>

<div class="col-sm-12 social-activities-iframe colorFont5">
    <?= LikeLink::widget(['object' => $containerUrl]); ?>
	<hr>
</div>
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

<div class="col-sm-12 comment-link">
    <?= Html::a(
    	Yii::t('CommentModule.widgets_views_form', 'Write a new comment...'),
    	'#',
    	[
    		'id' => 'load-iframe-comments',
    		'class' => 'btn btn-default',
    		'data' => [
    			'action-url' => Url::to(['/comment/comment/show', 'contentModel' => $objectModel, 'contentId' => $objectId, 'mode' => 'popup']),
    			// 'action-click' => 'ui.modal.load',
    			// 'modal-id' => 'write-comment-modal',
    		],
    	]
    ) ?>
</div>

<script type="text/javascript">
    $('#load-iframe-comments').on('click', function(e) {
        e.preventDefault();
        loadIframeComments($(this).attr('data-action-url'))
    });	
</script>