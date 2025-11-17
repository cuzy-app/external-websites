<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\components\View;
use humhub\modules\externalWebsites\models\forms\SpaceSettingsForm;
use humhub\widgets\modal\Modal;
use humhub\widgets\modal\ModalButton;


/**
 * @var $this View
 * @var $model SpaceSettingsForm
 */
?>

<?php $form = Modal::beginFormDialog([
    'title' => Yii::t('ExternalWebsitesModule.base', 'Settings'),
    'footer' => ModalButton::cancel() . ' ' . ModalButton::save()->submit(),
]) ?>

    <?= $form->field($model, 'urlToRedirect')->textInput(['placeholder' => 'https://www.my-external-website.tdl', 'autofocus' => '']) ?>
    <?= $form->field($model, 'preventLeavingSpace')->checkbox() ?>

<?php Modal::endFormDialog() ?>
