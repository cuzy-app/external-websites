<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use yii\helpers\Html;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\externalWebsites\widgets\FirstCommentForm;

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
}
// If Humhub is embedded
else {
    humhub\modules\externalWebsites\assets\EmbeddedAssets::register($this);
}
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="wall-entry-controls">
            <?php if ($showPermalink) : ?>
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
            <?php endif; ?>

            <?php if ($page !== null): ?>
                <?php if ($showLikes) : ?>
                    &middot; <?= LikeLink::widget(['object' => $page]); ?>
                <?php endif; ?>
                <?php if ($showComments) : ?>
                    &middot; <i class="fa fa-comment"></i> <?= CommentLink::widget(['object' => $page]); ?>
                <?php endif; ?>
            <?php endif ?>
        </div>

        <?php if ($showComments): ?>
            <?php if ($page !== null): ?>
                <?= Comments::widget(['object' => $page]) ?>
            <?php else: ?>
                <?= FirstCommentForm::widget([
                    'contentContainer' => $contentContainer,
                    'websiteId' => $website->id,
                    'title' => $title,
                    'pageUrl' => $pageUrl,
                ]) ?>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>

<?php if (!$showComments): ?>
    <script type="text/javascript">
        $(function(){
            $('#ew-website > .panel-body > .row').css({'display': 'flex', 'flex-direction': 'column-reverse'});
            $('#ew-page-addons > .panel').css({'box-shadow': 'none'});
        });
    </script>
<?php endif ?>