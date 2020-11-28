<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use humhub\libs\Html;
use yii\helpers\Url;
use humhub\widgets\ModalDialog;
use humhub\widgets\ModalButton;
use yii\bootstrap\ActiveForm;
use humhub\modules\externalWebsites\widgets\IconSelect;

/**
 * @var $this \humhub\components\View
 * @var $model \humhub\modules\calendarEventsExtension\models\forms\EventForm
 */
?>

<?php ModalDialog::begin([
	'header' => Yii::t('ExternalWebsitesModule.base', 'Website edition'),
]) ?>
	<div class="modal-body">
        <?php $form = ActiveForm::begin(); ?>
        	<?= $form->field($model, 'title')->textInput() ?>
            <?= IconSelect::widget(['model' => $model]) ?>
            <?= $form->field($model, 'first_page_url')->textInput() ?>
            <?= $form->field($model, 'show_in_menu')->radioList($model->yesNoList) ?>
            <?= $form->field($model, 'sort_order')->textInput() ?>
            <?= $form->field($model, 'remove_from_url_title')->textInput() ?>
            <?= $form->field($model, 'hide_sidebar')->radioList($model->yesNoList) ?>
            <?= $form->field($model, 'default_content_visibility')->dropDownList($model->contentVisibilityList) ?>
            <?= $form->field($model, 'default_content_archived')->radioList($model->yesNoList) ?>
            <?= Html::saveButton() ?>
        <?php ActiveForm::end(); ?>
	</div>
	<div class="modal-footer">
		<?= ModalButton::cancel(Yii::t('base', 'Close')) ?>
	</div>
<?php ModalDialog::end()?>