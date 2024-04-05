<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\modules\externalWebsites\models\forms\WebsiteForm;
use humhub\modules\space\models\Space;
use humhub\modules\ui\form\widgets\IconPicker;
use humhub\modules\ui\form\widgets\SortOrderField;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\UserPickerField;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this View
 * @var $model WebsiteForm
 * @var $contentContainer Space
 */
?>

<?php ModalDialog::begin([
    'header' => Yii::t('ExternalWebsitesModule.base', 'Add a website'),
]) ?>
<div class="modal-body">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'icon')->widget(IconPicker::class) ?>
    <?= $form->field($model, 'humhub_is_embedded')->checkbox() ?>
    <?= $form->field($model, 'first_page_url')->textInput() ?>
    <?= $form->field($model, 'page_url_params_to_remove')->textInput() ?>
    <?= $form->field($model, 'show_in_menu')->checkbox() ?>
    <?= $form->field($model, 'sort_order')->widget(SortOrderField::class) ?>
    <?= $form->field($model, 'remove_from_url_title')->textInput() ?>
    <?= $form->field($model, 'layout')->dropDownList($model->getLayoutList()) ?>
    <?= $form->field($model, 'default_content_visibility')->dropDownList($model->getContentVisibilityList()) ?>
    <?= $form->field($model, 'default_content_archived')->checkbox() ?>
    <?= UserPickerField::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'created_by',
        'maxSelection' => 1,
        'itemKey' => 'id', // TODO remove this line (see TODO in WebsiteForm)
    ]) ?>
    <?= Html::submitButton(
        Icon::get('plus') . ' ' . Yii::t('ExternalWebsitesModule.base', 'Add this website'),
        [
            'class' => 'btn btn-primary',
        ]
    ) ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="modal-footer">
    <?= ModalButton::cancel(Yii::t('base', 'Close')) ?>
</div>
<?php ModalDialog::end() ?>
