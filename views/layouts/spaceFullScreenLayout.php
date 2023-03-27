<?php

use humhub\modules\externalWebsites\controllers\WebsiteController;
use humhub\modules\space\widgets\SpaceContent;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\FooterMenu;
use yii\helpers\Html;

/**
 * @var \humhub\modules\ui\view\components\View $this
 * @var \humhub\modules\space\models\Space $space
 * @var string $content
 */

/** @var WebsiteController $context */
$context = $this->context;
$space = $context->contentContainer;
$website = $context->website;

?>
<style>
    #ew-space-full-screen-layout .breadcrumb {
        background-color: transparent;
    }

    #ew-space-full-screen-layout .breadcrumb > li + li:before {
        content: '>';
    }
</style>

<div id="ew-space-full-screen-layout" class="container space-layout-container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= Icon::get('home') ?>
                        <a href="<?= $space->createUrl() ?>"><?= Html::encode($space->name) ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= Icon::get($website->icon) ?>
                        <?= Html::encode($website->title) ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row space-content">
        <div class="col-md-12 layout-content-container">
            <?= SpaceContent::widget(['contentContainer' => $space, 'content' => $content]) ?>
        </div>
    </div>

    <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_FULL_PAGE]); ?>
</div>
