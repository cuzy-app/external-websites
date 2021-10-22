<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\widgets\UserPickerField;
use humhub\widgets\Button;
use yii\helpers\Html;
use humhub\widgets\GridView;
use humhub\modules\externalWebsites\models\forms\WebsiteForm;

/**
 * @var $this \humhub\modules\ui\view\components\View
 * @var $contentContainer \humhub\modules\space\models\Space
 * @var $searchModel \humhub\modules\externalWebsites\models\WebsiteSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$websiteForm = new WebsiteForm;
?>

<div id="ew-manage-websites" class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Yii::t('ExternalWebsitesModule.base', 'Websites management') ?></strong>
    </div>

    <div class="panel-body">
        <?= Button::success(Yii::t('ExternalWebsitesModule.base', 'Add a website'))
            ->icon('plus')
            ->link($contentContainer->createUrl('/external-websites/manage/add-website'))
            ->action('ui.modal.load') ?>

        <?= Button::defaultType(Yii::t('ExternalWebsitesModule.base', 'Settings'))
            ->icon('cogs')
            ->link($contentContainer->createUrl('/external-websites/manage/space-settings'))
            ->action('ui.modal.load')
            ->right() ?>

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
                    return Icon::get($model->icon);
                }
            ],
            [
                'attribute' => 'humhub_is_embedded',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->humhub_is_embedded ? Icon::get('check') : '';
                }
            ],
            [
                'attribute' => 'first_page_url',
            ],
            [
                'attribute' => 'show_in_menu',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->show_in_menu ? Icon::get('check') : '';
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
                'value' => function ($model) {
                    return $model->hide_sidebar ? Icon::get('check') : '';
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
                'value' => function ($model) {
                    return $model->default_content_archived ? Icon::get('check') : '';
                }
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'value' => function ($model) use ($contentContainer) {
                    return
                        Html::beginForm($contentContainer->createUrl('/external-websites/manage/change-owner', ['id' => $model->id])).
                        UserPickerField::widget([
                            'id' => 'website-owner-picker-'.$model->id,
                            'model' => $model,
                            'attribute' => 'created_by',
                            'maxSelection' => 1,
                            'itemKey' => 'id',
                            'placeholder' => Yii::t('ExternalWebsitesModule.base', 'Website owner (related contents for comments will be created with this user)'),
                        ]).
                        Html::submitButton(
                            Icon::get('check'),
                            [
                                'class' => 'btn btn-primary pull-right',
                            ]
                        ).
                        Html::endForm();
                }
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => function ($model) use ($contentContainer) {
                    return
                        Button::primary()
                            ->icon('pencil')
                            ->link($contentContainer->createUrl('/external-websites/manage/edit-website', ['websiteId' => $model->id]))
                            ->action('ui.modal.load')
                            ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Edit this website')).
                        '<br><br>'.
                        Button::danger()
                            ->icon('trash')
                            ->link($contentContainer->createUrl('/external-websites/manage/delete-website', ['websiteId' => $model->id]))
                            ->action('ui.modal.load')
                            ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Delete this website'))
                            ->confirm(Yii::t('ExternalWebsitesModule.base', 'Are you sure you want to delete this website?'));
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