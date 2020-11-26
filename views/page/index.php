<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\externalWebsites\widgets\FirstCommentForm;

/**
 * @var $space humhub\modules\jdn\models\Space
 * @var $website humhub\modules\externalWebsites\models\Website
 * @var $page humhub\modules\externalWebsites\models\Page
 * @var $pageUrl string page url
 * @var $pageTitle string page title
 * @var $permalink string
 * @var $humhubIsHost boolean
 */

// If Humhub is host
if ($humhubIsHost) {
    $this->registerJsConfig('externalWebsites.Host', [
        'hideSidebar' => $website['hide_sidebar'],
        'permalink' => $permalink,
    ]);
}
// If Humhub is guest
else {
    humhub\modules\externalWebsites\assets\GuestAssets::register($this);
}
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="wall-entry-controls">
            <?= Html::a(
                ' '.Yii::t('ExternalWebsitesModule.base', 'Permalink'),
                '#',
                [
                    'class' => 'permalink',
                    'data' => [
                        'action-click' => 'content.permalink',
                        'content-permalink' => $permalink,
                    ]
                ]
            ) ?>

            <?php if ($page !== null): ?>
                &middot; <?= LikeLink::widget(['object' => $page]); ?>
                &middot; <i class="fa fa-comment"></i> <?= CommentLink::widget(['object' => $page]); ?>
            <?php endif ?>
        </div>

        <?php if ($page !== null): ?>
            <?= Comments::widget(['object' => $page]) ?>
        <?php else: ?>
            <?= FirstCommentForm::widget([
                'space' => $space,
                'websiteId' => $website['id'],
                'pageUrl' => $pageUrl,
                'pageTitle' => $pageTitle,
            ]) ?>
        <?php endif ?>
    </div>
</div>