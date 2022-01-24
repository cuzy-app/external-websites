<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use humhub\libs\Html;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\externalWebsites\assets\EmbeddedAssets;
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

// If Humhub is host
if (!$humhubIsEmbedded) {
    $this->registerJsConfig('externalWebsites.Host', [
        'hideSidebar' => $website->hide_sidebar,
        'permalink' => $permalink,
    ]);
} // If Humhub is embedded
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
            &middot; <?= LikeLink::widget(['object' => $page]); ?>
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
    $('#ew-page-iframed').removeClass('col-md-9').addClass('col-md-12');
    $('#ew-page-addons').removeClass('col-md-3').addClass('col-md-12');
    <?php else: ?>
    $('#ew-page-iframed').removeClass('col-md-12').addClass('col-md-9');
    $('#ew-page-addons').removeClass('col-md-12').addClass('col-md-3');
    <?php endif; ?>

    $(function () {
        humhub.modules.externalWebsites.Host.updateBrowserUrlAndToggleSidebar();
    });
</script>