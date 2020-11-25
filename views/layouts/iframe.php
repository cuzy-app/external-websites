<?php

use humhub\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= Html::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php $this->head() ?>
    <?= $this->render('@humhub/views/layouts/head'); ?>
    <meta charset="<?= Yii::$app->charset ?>">
</head>

<body class="iframe-container" style="padding: 0 !important;">
	<?php $this->beginBody() ?>
	<?= $content; ?>
	<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
