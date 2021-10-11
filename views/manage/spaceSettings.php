<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
use yii\helpers\Url;
use humhub\widgets\ModalDialog;
use humhub\widgets\ModalButton;
use yii\bootstrap\ActiveForm;


/**
 * @var $this \humhub\modules\ui\view\components\View
 * @var $model \humhub\modules\externalWebsites\models\forms\SpaceSettingsForm
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
<?php ModalDialog::end()?>