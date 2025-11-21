<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\components\View;
use humhub\helpers\Html;
use humhub\modules\externalWebsites\models\forms\WebsiteForm;
use humhub\modules\externalWebsites\models\WebsiteSearch;
use humhub\modules\space\models\Space;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\Image;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\GridView;
use humhub\widgets\modal\ModalButton;
use yii\data\ActiveDataProvider;

/**
 * @var $this View
 * @var $contentContainer Space
 * @var $searchModel WebsiteSearch
 * @var $dataProvider ActiveDataProvider
 */

$websiteForm = new WebsiteForm();
?>

<div id="ew-manage-websites" class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Yii::t('ExternalWebsitesModule.base', 'Websites management') ?></strong>
    </div>

    <div class="panel-body">
        <?= ModalButton::light(Yii::t('ExternalWebsitesModule.base', 'Settings'))
            ->icon('cogs')
            ->load($contentContainer->createUrl('/external-websites/manage/space-settings'))
            ->right() ?>

        <?= ModalButton::success(Yii::t('ExternalWebsitesModule.base', 'Add a website'))
            ->icon('plus')
            ->load($contentContainer->createUrl('/external-websites/manage/add-website')) ?>

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
                'value' => static fn($model) => Icon::get($model->icon),
            ],
            [
                'attribute' => 'humhub_is_embedded',
                'format' => 'raw',
                'value' => static fn($model) => $model->humhub_is_embedded ? Icon::get('check') : '',
            ],
            [
                'attribute' => 'first_page_url',
            ],
            [
                'attribute' => 'page_url_params_to_remove',
                'format' => 'raw',
                'value' => static fn($model) => implode(',', $model->getPageUrlParamsToRemove()),
            ],
            [
                'attribute' => 'show_in_menu',
                'format' => 'raw',
                'value' => static fn($model) => $model->show_in_menu ? Icon::get('check') : '',
            ],
            [
                'attribute' => 'sort_order',
            ],
            [
                'attribute' => 'remove_from_url_title',
            ],
            [
                'attribute' => 'layout',
                'format' => 'raw',
                'value' => static fn($model) => $websiteForm->getLayoutList()[$model->layout] ?? '',
            ],
            [
                'attribute' => 'default_content_visibility',
                'format' => 'raw',
                'value' => static fn($model) => $websiteForm->getContentVisibilityList()[$model->default_content_visibility] ?? '',
            ],
            [
                'attribute' => 'default_content_archived',
                'format' => 'raw',
                'value' => static fn($model) => $model->default_content_archived ? Icon::get('check') : '',
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'value' => static function ($model) use ($contentContainer) {
                    $user = User::findOne($model->created_by);
                    return
                        Image::widget(['user' => $user, 'width' => 35, 'showTooltip' => true]) . '<br>'
                        . Html::containerLink($user);
                },
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => static fn($model) => ModalButton::primary()
                    ->icon('pencil')
                    ->load($contentContainer->createUrl('/external-websites/manage/edit-website', ['websiteId' => $model->id]))
                    ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Edit this website'))
                . '<br><br>'
                . ModalButton::danger()
                    ->icon('trash')
                    ->load($contentContainer->createUrl('/external-websites/manage/delete-website', ['websiteId' => $model->id]))
                    ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Delete this website'))
                    ->confirm(Yii::t('ExternalWebsitesModule.base', 'Are you sure you want to delete this website?'), Yii::t('ExternalWebsitesModule.base', 'All related comments will be deleted.')),
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
