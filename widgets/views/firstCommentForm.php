<?php
use humhub\modules\content\Module;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\file\widgets\UploadButton;
use humhub\modules\file\widgets\FilePreview;
use humhub\modules\comment\models\Comment;

/* @var $this View */
/* @var $objectModel string */
/* @var $model Comment */
/* @var $id string unique object id */
/* @var $isNestedComment boolean */
/** @var Module $contentModule */

/**
 * This form is nearly identical to humhub\modules\comment\widgets\views\form.php
 * Differences are :
 * - vars
 * - hidden inputs
 * - .comment-container and .comment tags (to show new comment after submit)
 * - no hr tag
 * 
 * @var $this View
 * @var $this \humhub\modules\ui\view\components\View
 * @var $id string for tags attributes
 * @var $model Commen
 * @var $containerPageId int
 * @var $iframeUrl string
 * @var $iframeTitle string
 * @var Module $contentModule
 */

/** @var \humhub\modules\content\Module $contentModule */
$contentModule = Yii::$app->getModule('content');
$submitUrl = Url::to(['/iframe/comment/post']);
?>

<div class="well well-small comment-container" id="comment_<?= $id; ?>">
    
    <?php // This div.comment tag is the place where the new comment will be shown after submitting the form ?>
    <div class="comment <?php if (Yii::$app->user->isGuest): ?>guest-mode<?php endif; ?>"
         id="comments_area_<?= $id; ?>">
    </div>

    <div id="comment_create_form_<?= $id ?>" class="comment_create" data-ui-widget="comment.Form">

        <hr style="display: none;">

        <?php $form = ActiveForm::begin(['action' => $submitUrl]) ?>

        <?= Html::hiddenInput('objectModel', $objectModel) ?>

        <?= Html::hiddenInput('iframeUrl', $iframeUrl); ?>
        <?= Html::hiddenInput('iframeTitle', $iframeTitle); ?>
        <?= Html::hiddenInput('containerPageId', $containerPageId); ?>

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

            <div class="comment-buttons">
                <?= UploadButton::widget([
                    'id' => 'comment_create_upload_' . $id,
                    'tooltip' => Yii::t('ContentModule.base', 'Attach Files'),
                    'options' => ['class' => 'main_comment_upload'],
                    'progress' => '#comment_create_upload_progress_' . $id,
                    'preview' => '#comment_create_upload_preview_' . $id,
                    'dropZone' => '#comment_create_form_' . $id,
                    'max' => $contentModule->maxAttachedFiles
                ]) ?>

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
        ]) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>

<script type="text/javascript">
    $('#comment_create_form_<?= $id; ?> form').on('submit', function(event) {
        $(this).parent().children('hr').show();
    });
</script>