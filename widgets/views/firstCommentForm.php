<?php

use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\file\widgets\UploadButton;
use humhub\modules\file\widgets\FilePreview;

/**
 * This form is nearly identical to humhub\modules\comment\widgets\form.php
 * Differences are :
 * - vars
 * - hidden inputs
 * - .comment-container and .comment tags (to show new comment after submit)
 * - no hr tag
 * 
 * @var $this \humhub\modules\ui\view\components\View
 * @var $id string for tags attributes
 * @var $containerPageId int
 * @var $iframeUrl string
 * @var $iframeTitle string
 */

/** @var \humhub\modules\content\Module $contentModule */
$contentModule = Yii::$app->getModule('content');
$submitUrl = Url::to(['/iframe/comment/post']);
?>

<div class="well well-small comment-container" id="comment_<?= $id; ?>">
    <div class="comment <?php if (Yii::$app->user->isGuest): ?>guest-mode<?php endif; ?>"
         id="comments_area_<?= $id; ?>">
    </div>
    <div id="comment_create_form_<?= $id; ?>" class="comment_create" data-ui-widget="comment.Form">

        <hr style="display: none;">

        <?= Html::beginForm('#'); ?>
        <?= Html::hiddenInput('iframeUrl', $iframeUrl); ?>
        <?= Html::hiddenInput('iframeTitle', $iframeTitle); ?>
        <?= Html::hiddenInput('containerPageId', $containerPageId); ?>

        <div class="comment-create-input-group">
            <?= RichTextField::widget([
                'id' => 'newCommentForm_' . $id,
                'layout' => RichTextField::LAYOUT_INLINE,
                'pluginOptions' => ['maxHeight' => '300px'],
                'placeholder' => Yii::t('CommentModule.base', 'Write a new comment...'),
                'name' => 'message',
                'events' => [
                    'scroll-active' => 'comment.scrollActive',
                    'scroll-inactive' => 'comment.scrollInactive'
                ]
            ]); ?>

            <div class="comment-buttons">
                <?= UploadButton::widget([
                    'id' => 'comment_create_upload_' . $id,
                    'options' => ['class' => 'main_comment_upload'],
                    'progress' => '#comment_create_upload_progress_' . $id,
                    'preview' => '#comment_create_upload_preview_' . $id,
                    'dropZone' => '#comment_create_form_' . $id,
                    'max' => $contentModule->maxAttachedFiles
                ]); ?>

                <?= Button::defaultType(Yii::t('CommentModule.base', 'Send'))
                    ->cssClass('btn-comment-submit')
                    ->action('submit', $submitUrl)->submit()->sm() ?>
            </div>
        </div>

        <div id="comment_create_upload_progress_<?= $id ?>" style="display:none;margin:10px 0px;"></div>

        <?= FilePreview::widget([
            'id' => 'comment_create_upload_preview_' . $id,
            'options' => ['style' => 'margin-top:10px'],
            'edit' => true
        ]); ?>

        <?= Html::endForm(); ?>
    </div>
</div>

<script type="text/javascript">
    $('#comment_create_form_<?= $id; ?> form').on('submit', function(event) {
        $(this).parent().children('hr').show();
    });
</script>