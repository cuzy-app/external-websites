<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $space humhub\modules\jdn\models\Space
 * @var $containerUrl humhub\modules\iframe\models\ContainerUrl
 */
?>


<div>
	<p>
		<?= Html::a(
			$containerUrl->containerPage['title'],
			$space->createUrl('/iframe/page?title='.urlencode($containerUrl->containerPage['title'])),
			[]
		) ?>
		<i class="fa fa-angle-double-right"></i>
		<?= Html::a(
			$containerUrl['title'],
			$space->createUrl('/iframe/page?title='.urlencode($containerUrl->containerPage['title']).'&urlId='.$containerUrl['id']),
			[]
		) ?>
	</p>
	<p>
		<strong><?= Html::a(
			Yii::t('IframeModule.base', 'Show the page'),
			$space->createUrl('/iframe/page?title='.urlencode($containerUrl->containerPage['title']).'&urlId='.$containerUrl['id']),
			[]
		) ?></strong>
	</p>
</div>