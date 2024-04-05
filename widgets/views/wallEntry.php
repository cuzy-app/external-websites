<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use yii\helpers\Html;

/**
 * @var $page humhub\modules\externalWebsites\models\Page
 */
?>


<div>
    <p>
        <strong><?= Html::encode($page->title) ?></strong>
    </p>
    <p>
        <strong><?= Html::a(
                Yii::t('ExternalWebsitesModule.base', 'Show the page'),
                $page->url,
                [
                    'class' => 'btn btn-primary',
                    'target' => ($page->website->humhub_is_embedded) ? '_blank' : '_self',
                ]
            ) ?></strong>
    </p>
</div>
