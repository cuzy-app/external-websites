<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $space humhub\modules\space\models\Space
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