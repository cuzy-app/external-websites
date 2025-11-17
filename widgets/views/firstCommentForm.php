<?php

use humhub\components\View;
use humhub\helpers\Html;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\Module;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\file\handler\BaseFileHandler;
use humhub\modules\file\widgets\FileHandlerButtonDropdown;
use humhub\modules\file\widgets\FilePreview;
use humhub\modules\file\widgets\UploadButton;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;
use yii\helpers\Url;

/* @var $this View */
/* @var $objectModel string */
/* @var $model Comment */
/* @var $id string unique object id */
/* @var $isNestedComment boolean */
/* @var Module $contentModule */

/**
 * This form is nearly identical to humhub\modules\comment\widgets\views\form.php
 * Differences are :
 * - vars
 * - hidden inputs
 * - .comment-container and .comment tags (to show new comment after submit)
 * - no hr tag
 *
 * @var $this View
 * @var $this View
 * @var $id string for tags attributes
 * @var $model Comment
 * @var $websiteId int
 * @var $pageUrl string
 * @var $title string
 * @var $contentModule Module
 * @var $fileHandlers BaseFileHandler[]
 */

/** @var Module $contentModule */
$contentModule = Yii::$app->getModule('content');
$submitUrl = Url::to(['/external-websites/comment/post']);
?>

<div class="bg-light p-3 comment-container" id="comment_<?= $id; ?>">

    <?php // This div.comment tag is the place where the new comment will be shown after submitting the form ?>
    <div class="comment <?php if (Yii::$app->user->isGuest): ?>guest-mode<?php endif; ?>"
         id="comments_area_<?= $id; ?>">
    </div>

    <div id="comment_create_form_<?= $id ?>" class="comment_create" data-ui-widget="comment.Form">

        <?php $form = ActiveForm::begin(['action' => $submitUrl]) ?>

        <?= Html::hiddenInput('objectModel', $objectModel) ?>

        <?= Html::hiddenInput('pageUrl', $pageUrl); ?>
        <?= Html::hiddenInput('title', $title); ?>
        <?= Html::hiddenInput('websiteId', $websiteId); ?>

        <div class="comment-create-input-group">
            <?= $form->field($model, 'message')->widget(RichTextField::class, [
                'id' => 'newCommentForm_' . $id,
                'layout' => RichTextField::LAYOUT_INLINE,
                'pluginOptions' => ['maxHeight' => '300px'],
                'placeholder' => Yii::t('CommentModule.base', 'Write a new comment...'),
                'name' => 'message',
                'events' => [
                    'scroll-active' => 'comment.scrollActive',
                    'scroll-inactive' => 'comment.scrollInactive'
                ]
            ])->label(false) ?>

            <div class="comment-buttons"><?php
                $uploadButton = UploadButton::widget([
                    'id' => 'comment_create_upload_' . $id,
                    'tooltip' => Yii::t('ContentModule.base', 'Attach Files'),
                    'options' => ['class' => 'main_comment_upload'],
                    'progress' => '#comment_create_upload_progress_' . $id,
                    'preview' => '#comment_create_upload_preview_' . $id,
                    'dropZone' => '#comment_create_form_' . $id,
                    'max' => $contentModule->maxAttachedFiles,
                    'cssButtonClass' => 'btn-sm btn-info',
                ]);
                echo FileHandlerButtonDropdown::widget([
                    'primaryButton' => $uploadButton,
                    'handlers' => $fileHandlers,
                    'cssButtonClass' => 'btn-info btn-sm',
                ]);
                echo Button::info()
                    ->icon('send')
                    ->cssClass('btn-comment-submit')->sm()
                    ->action('submit', $submitUrl)->submit();
                ?></div>
        </div>

        <div id="comment_create_upload_progress_<?= $id ?>" style="display:none;margin:10px 0;"></div>

        <?= FilePreview::widget([
            'id' => 'comment_create_upload_preview_' . $id,
            'options' => ['style' => 'margin-top:10px'],
            'edit' => true
        ]) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>

<script <?= Html::nonce() ?>>
    $('#comment_create_form_<?= $id; ?> form').on('submit', function (event) {
        $(this).parent().children('hr').show();
    });
</script>
