<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $space humhub\modules\jdn\models\Space
 * @var $page humhub\modules\externalWebsites\models\Page
 */
?>


<div>
	<p>
		<?= Html::a(
			$page->website->title,
			$page->website->url
		) ?>
		<i class="fa fa-angle-double-right"></i>
		<?= Html::a(
			$page->title,
			$page->url
		) ?>
	</p>
	<p>
		<strong><?= Html::a(
			Yii::t('ExternalWebsitesModule.base', 'Show the page'),
			$page->url
		) ?></strong>
	</p>
</div>