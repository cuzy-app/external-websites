<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\helpers\Html;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\externalWebsites\assets\EmbeddedAssets;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\widgets\FirstCommentForm;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\ui\icon\widgets\Icon;

/**
 * @var $contentContainer humhub\modules\space\models\Space
 * @var $website humhub\modules\externalWebsites\models\Website
 * @var $page humhub\modules\externalWebsites\models\Page
 * @var $pageUrl string page url
 * @var $title string page title
 * @var $permalink string
 * @var $showComments boolean default true
 * @var $showLikes boolean default true
 * @var $showPermalink boolean default true
 * @var $humhubIsEmbedded integer (0 or 1)
 */

// If HumHub is host
if (!$humhubIsEmbedded) {
    $this->registerJsConfig('externalWebsites.Host', [
        'hideSidebar' => $website->layout === Website::LAYOUT_MENU_COLLAPSED,
        'permalink' => $permalink,
    ]);
} // If HumHub is embedded
else {
    EmbeddedAssets::register($this);
}
?>

<div class="wall-entry-controls">
    <?php if ($showPermalink): ?>
        <?= Html::a(
            ' ' . Yii::t('ContentModule.base', 'Permalink'),
            '#',
            [
                'class' => 'permalink',
                'data' => [
                    'action-click' => 'content.permalink',
                    'content-permalink' => $permalink,
                ]
            ]
        ) ?>
    <?php endif; ?>

    <?php if ($page !== null && $page->content->canView()): ?>
        <?php if ($showLikes): ?>
            &middot; <?= LikeLink::widget(['object' => $page]) ?>
        <?php endif; ?>
        <?php if ($showComments): ?>
            &middot; <?= Icon::get('comment') ?> <?= CommentLink::widget(['object' => $page]); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if ($showComments): ?>
    <?php if ($page !== null): ?>
        <?php if ($page->content->canView()): ?>
            <?= Comments::widget(['object' => $page]) ?>
        <?php endif; ?>
    <?php else: ?>
        <?= FirstCommentForm::widget([
            'contentContainer' => $contentContainer,
            'websiteId' => $website->id,
            'title' => $title,
            'pageUrl' => $pageUrl,
        ]) ?>
    <?php endif; ?>
<?php endif; ?>

<script <?= Html::nonce() ?>>
    <?php if (!$showComments): ?>
    $('#ew-page-iframed').removeClass('col-lg-9').addClass('col-lg-12');
    $('#ew-page-addons').removeClass('col-lg-3').addClass('col-lg-12');
    <?php else: ?>
    $('#ew-page-iframed').removeClass('col-lg-12').addClass('col-lg-9');
    $('#ew-page-addons').removeClass('col-lg-12').addClass('col-lg-3');
    <?php endif; ?>

    $(function () {
        humhub.modules.externalWebsites.Host.updateBrowserUrl();
    });
</script>
