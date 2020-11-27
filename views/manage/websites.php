<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;

/**
 * @var $this \humhub\components\View
 * @var $contentContainer \humhub\modules\space\models\Space
 * @var $searchModel \humhub\modules\calendarEventsExtension\models\EventSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

?>

<div id="ew-website" class="panel panel-default" data-container-page-id="<?= $website->id ?>">
    <div class="panel-heading">
        <strong><?= Yii::t('ExternalWebsitesModule.base', 'Websites managment') ?></strong>
    </div>

    <?= Html::a(
        '<i class="fa fa-plus"></i> '.Yii::t('ExternalWebsitesModule.base', 'Add a website'),
        $contentContainer->createUrl('/external-websites/event/add'),
        [
            'class' => 'btn btn-primary',
            'data-target' => '#globalModal',
        ]
    ) ?>

    <div class="panel-body">
        <?= GridView::widget([
            'id' => 'ew-websites-grid',
            'dataProvider' => $dataProvider,
            'summary' => '',
            // 'columns' => $columns,
        ]); ?>
    </div>
</div>