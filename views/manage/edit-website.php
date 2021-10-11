<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
use humhub\modules\ui\form\widgets\IconPicker;
use humhub\widgets\ModalDialog;
use humhub\widgets\ModalButton;
use yii\bootstrap\ActiveForm;

/**
 * @var $this \humhub\modules\ui\view\components\View
 * @var $model \humhub\modules\externalWebsites\models\forms\WebsiteForm
 */
?>

<?php ModalDialog::begin([
	'header' => Yii::t('ExternalWebsitesModule.base', 'Website edition'),
]) ?>
	<div class="modal-body">
        <?php $form = ActiveForm::begin(); ?>
        	<?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'icon')->widget(IconPicker::class) ?>
            <?= $form->field($model, 'humhub_is_embedded')->checkbox() ?>
            <?= $form->field($model, 'first_page_url')->textInput() ?>
            <?= $form->field($model, 'show_in_menu')->checkbox() ?>
            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'remove_from_url_title')->textInput() ?>
            <?= $form->field($model, 'hide_sidebar')->checkbox() ?>
            <?= $form->field($model, 'default_content_visibility')->dropDownList($model->contentVisibilityList) ?>
            <?= $form->field($model, 'default_content_archived')->checkbox() ?>
            <?= Html::saveButton() ?>
        <?php ActiveForm::end(); ?>
	</div>
	<div class="modal-footer">
		<?= ModalButton::cancel(Yii::t('base', 'Close')) ?>
	</div>
<?php ModalDialog::end()?>