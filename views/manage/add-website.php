<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\modules\ui\form\widgets\IconPicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use humhub\widgets\ModalDialog;
use humhub\widgets\ModalButton;

/**
 * @var $this \humhub\modules\ui\view\components\View
 * @var $model \humhub\modules\externalWebsites\models\forms\WebsiteForm
 */
?>

<?php ModalDialog::begin([
	'header' => Yii::t('ExternalWebsitesModule.base', 'Add a website'),
]) ?>
	<div class="modal-body">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'icon')->widget(IconPicker::class) ?>
            <?= $form->field($model, 'humhub_is_embedded')->radioList($model->yesNoList) ?>
            <?= $form->field($model, 'first_page_url')->textInput() ?>
            <?= $form->field($model, 'show_in_menu')->radioList($model->yesNoList) ?>
            <?= $form->field($model, 'sort_order')->textInput() ?>
            <?= $form->field($model, 'remove_from_url_title')->textInput() ?>
            <?= $form->field($model, 'hide_sidebar')->radioList($model->yesNoList) ?>
            <?= $form->field($model, 'default_content_visibility')->dropDownList($model->contentVisibilityList) ?>
            <?= $form->field($model, 'default_content_archived')->radioList($model->yesNoList) ?>
            <?= Html::submitButton(
                '<i class="fa fa-plus"></i> '.Yii::t('ExternalWebsitesModule.base', 'Add this website'),
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