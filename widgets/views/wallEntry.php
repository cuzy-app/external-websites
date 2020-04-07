<?php
use yii\helpers\Html;
use yii\helpers\Url;
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