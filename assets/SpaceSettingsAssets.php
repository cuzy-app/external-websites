<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;

/**
 * Assests to redirect URLs to an external website
 */
class SpaceSettingsAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $css = [
    ];

    public $js = [
        'js/humhub.externalWebsites.SpaceSettings.js',
    ];
}
