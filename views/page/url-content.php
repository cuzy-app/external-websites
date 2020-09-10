<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\like\widgets\LikeLink;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\iframe\widgets\FirstCommentForm;

/**
 * @var $space humhub\modules\jdn\models\Space
 * @var $containerPage humhub\modules\iframe\models\ContainerPage
 * @var $containerUrl humhub\modules\iframe\models\ContainerUrl
 * @var $iframeUrl string iframe page url
 * @var $iframeTitle string iframe page title
 */

$permalinkParams = [
    'title' => $containerPage['title'],
];
if ($containerUrl !== null) {
    $permalinkParams['urlId'] = $containerUrl['id'];
}
else {
    $permalinkParams['iframeUrl'] = $iframeUrl;
}
$permalink = $space->createUrl('/iframe/page', $permalinkParams);
//$permalink = Url::to($permalinkParams, true);
?>

<div id="url-content">

    <div class="col-sm-12 colorFont5">
        <div class="wall-entry-controls">
            <?= Html::a(
                ' '.Yii::t('IframeModule.base', 'Permalink'),
                '#',
                [
                    'class' => 'permalink',
                    'data' => [
                        'action-click' => 'content.permalink',
                        'content-permalink' => $permalink,
                    ]
                ]
            ) ?>

            <?php if ($containerUrl !== null): ?>
                &middot; <?= LikeLink::widget(['object' => $containerUrl]); ?>
                &middot; <i class="fa fa-comment"></i> <?= CommentLink::widget(['object' => $containerUrl]); ?>
            <?php else: ?>
                &middot; <?= FirstLikeLink::widget(['object' => $containerUrl]); ?>
            <?php endif ?>
        </div>
    </div>

    <div class="col-sm-12 comments">
        <?php if ($containerUrl !== null): ?>
            <?= Comments::widget(['object' => $containerUrl]) ?>
        <?php else: ?>
            <?= FirstCommentForm::widget([
                'space' => $space,
                'containerPageId' => $containerPage['id'],
                'iframeUrl' => $iframeUrl,
                'iframeTitle' => $iframeTitle,
            ]) ?>
        <?php endif ?>
    </div>

</div>

<script type="text/javascript">
    // Update browser URL
    window.history.replaceState({},'', '<?= $permalink ?>');

    // For module.js
    <?php if ($containerPage['hide_sidebar']) : ?>
        var hideSidebar = true;
    <?php else : ?>
        var hideSidebar = false;
    <?php endif; ?>
</script>