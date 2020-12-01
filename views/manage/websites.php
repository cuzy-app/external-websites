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
use humhub\modules\externalWebsites\models\forms\WebsiteForm;

/**
 * @var $this \humhub\components\View
 * @var $contentContainer \humhub\modules\space\models\Space
 * @var $searchModel \humhub\modules\calendarEventsExtension\models\EventSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$websiteForm = new WebsiteForm;
?>

<div id="ew-manage-websites" class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Yii::t('ExternalWebsitesModule.base', 'Websites managment') ?></strong>
    </div>

    <div class="panel-body">
        <?= Html::a(
            '<i class="fa fa-plus"></i> '.Yii::t('ExternalWebsitesModule.base', 'Add a website'),
            $contentContainer->createUrl('/external-websites/manage/add-website'),
            [
                'class' => 'btn btn-primary',
                'data-target' => '#globalModal',
            ]
        ) ?>

        <?php $columns = [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'min-width: 40px;'],
            ],
            [
                'attribute' => 'title',
                'headerOptions' => ['style' => 'min-width: 120px;'],
            ],
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<i class="fa '.$model->icon.'"></i>';
                }
            ],
            [
                'attribute' => 'humhub_is_host',
                'format' => 'raw',
                'value' => function ($model) use ($websiteForm) {
                    return $websiteForm->yesNoList[$model->humhub_is_host];
                }
            ],
            [
                'attribute' => 'first_page_url',
            ],
            [
                'attribute' => 'show_in_menu',
                'format' => 'raw',
                'value' => function ($model) use ($websiteForm) {
                    return $websiteForm->yesNoList[$model->show_in_menu];
                }
            ],
            [
                'attribute' => 'sort_order',
            ],
            [
                'attribute' => 'remove_from_url_title',
            ],
            [
                'attribute' => 'hide_sidebar',
                'format' => 'raw',
                'value' => function ($model) use ($websiteForm) {
                    return $websiteForm->yesNoList[$model->hide_sidebar];
                }
            ],
            [
                'attribute' => 'default_content_visibility',
                'format' => 'raw',
                'value' => function ($model) use ($websiteForm) {
                    return $websiteForm->contentVisibilityList[$model->default_content_visibility];
                }
            ],
            [
                'attribute' => 'default_content_archived',
                'format' => 'raw',
                'value' => function ($model) use ($websiteForm) {
                    return $websiteForm->yesNoList[$model->default_content_archived];
                }
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => function ($model) use ($contentContainer) {
                    return 
                        Html::a(
                            '<i class="fa fa-pencil"></i>',
                            $contentContainer->createUrl('/external-websites/manage/edit-website', ['websiteId' => $model->id]),
                            [
                                'class' => 'btn btn-primary tt',
                                'title' => Yii::t('ExternalWebsitesModule.base', 'Edit this website'),
                                'data-target' => '#globalModal',
                            ]
                        ).
                        '<br><br>'.
                        Html::a(
                            '<i class="fa fa-trash"></i>',
                            $contentContainer->createUrl('/external-websites/manage/delete-website', ['websiteId' => $model->id]),
                            [
                                'class' => 'btn btn-danger tt',
                                'title' => Yii::t('ExternalWebsitesModule.base', 'Delete this website'),
                                'data-confirm' => Yii::t('ExternalWebsitesModule.base', 'Are you sure you want to delete this website?'),
                            ]
                        );
                }
            ],
        ]; ?>


        <?= GridView::widget([
            'id' => 'ew-websites-grid',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => $columns,
        ]); ?>
    </div>
</div>