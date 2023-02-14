<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
use humhub\modules\externalWebsites\models\forms\WebsiteForm;
use humhub\modules\externalWebsites\models\WebsiteSearch;
use humhub\modules\space\models\Space;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\Image;
use humhub\widgets\Button;
use humhub\widgets\GridView;
use yii\data\ActiveDataProvider;

/**
 * @var $this View
 * @var $contentContainer Space
 * @var $searchModel WebsiteSearch
 * @var $dataProvider ActiveDataProvider
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
                'value' => static function ($model) {
                    return Icon::get($model->icon);
                }
            ],
            [
                'attribute' => 'humhub_is_embedded',
                'format' => 'raw',
                'value' => static function ($model) {
                    return $model->humhub_is_embedded ? Icon::get('check') : '';
                }
            ],
            [
                'attribute' => 'first_page_url',
            ],
            [
                'attribute' => 'page_url_params_to_remove',
                'format' => 'raw',
                'value' => static function ($model) {
                    return implode(',', $model->getPageUrlParamsToRemove());
                }
            ],
            [
                'attribute' => 'show_in_menu',
                'format' => 'raw',
                'value' => static function ($model) {
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
                'value' => static function ($model) {
                    return $model->hide_sidebar ? Icon::get('check') : '';
                }
            ],
            [
                'attribute' => 'default_content_visibility',
                'format' => 'raw',
                'value' => static function ($model) use ($websiteForm) {
                    return $websiteForm->getContentVisibilityList()[$model->default_content_visibility];
                }
            ],
            [
                'attribute' => 'default_content_archived',
                'format' => 'raw',
                'value' => static function ($model) {
                    return $model->default_content_archived ? Icon::get('check') : '';
                }
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'value' => static function ($model) use ($contentContainer) {
                    $user = User::findOne($model->created_by);
                    return
                        Image::widget(['user' => $user, 'width' => 35, 'showTooltip' => true]) . '<br>' .
                        Html::containerLink($user);
                }
            ],
            [
                'header' => '',
                'format' => 'raw',
                'value' => static function ($model) use ($contentContainer) {
                    return
                        Button::primary()
                            ->icon('pencil')
                            ->link($contentContainer->createUrl('/external-websites/manage/edit-website', ['websiteId' => $model->id]))
                            ->action('ui.modal.load')
                            ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Edit this website')) .
                        '<br><br>' .
                        Button::danger()
                            ->icon('trash')
                            ->link($contentContainer->createUrl('/external-websites/manage/delete-website', ['websiteId' => $model->id]))
                            ->action('ui.modal.load')
                            ->tooltip(Yii::t('ExternalWebsitesModule.base', 'Delete this website'))
                            ->confirm(Yii::t('ExternalWebsitesModule.base', 'Are you sure you want to delete this website?'), Yii::t('ExternalWebsitesModule.base', 'All related comments will be deleted.'));
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