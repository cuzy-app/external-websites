<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\modules\ui\form\widgets\IconPicker;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\widgets\UserPickerField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use humhub\widgets\ModalDialog;
use humhub\widgets\ModalButton;

/**
 * @var $this \humhub\modules\ui\view\components\View
 * @var $model \humhub\modules\externalWebsites\models\forms\WebsiteForm
 * @var $contentContainer \humhub\modules\space\models\Space
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
            <?= $form->field($model, 'show_in_menu')->checkbox() ?>
            <?= $form->field($model, 'sort_order')->textInput() ?>
            <?= $form->field($model, 'remove_from_url_title')->textInput() ?>
            <?= $form->field($model, 'hide_sidebar')->checkbox() ?>
            <?= $form->field($model, 'default_content_visibility')->dropDownList($model->getContentVisibilityList()) ?>
            <?= $form->field($model, 'default_content_archived')->checkbox() ?>
            <?= UserPickerField::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'created_by',
                'maxSelection' => 1,
                'itemKey' => 'id',
            ]) ?>
            <?= Html::submitButton(
                Icon::get('plus').' '.Yii::t('ExternalWebsitesModule.base', 'Add this website'),
                [
                    'class' => 'btn btn-primary',
                ]
            ) ?>
        <?php ActiveForm::end(); ?>
	</div>
	<div class="modal-footer">
		<?= ModalButton::cancel(Yii::t('base', 'Close')) ?>
	</div>
<?php ModalDialog::end()?>