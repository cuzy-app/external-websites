<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
use humhub\modules\externalWebsites\models\forms\SpaceSettingsForm;
use humhub\modules\ui\view\components\View;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;


/**
 * @var $this View
 * @var $model SpaceSettingsForm
 */
?>

<?php ModalDialog::begin([
    'header' => Yii::t('ExternalWebsitesModule.base', 'Settings'),
]) ?>
<div class="modal-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'urlToRedirect')->textInput(['placeholder' => 'https://www.my-external-website.tdl']) ?>
    <?= $form->field($model, 'preventLeavingSpace')->checkbox() ?>

    <br>
    <div class="input-group-btn">
        <?= Html::saveButton() ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="modal-footer">
    <?= ModalButton::cancel(Yii::t('base', 'Close')) ?>
</div>
<?php ModalDialog::end() ?>
