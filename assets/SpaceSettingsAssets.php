<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;
use yii\web\View;

/**
 * Assests to redirect URLs to an external website
 */
class SpaceSettingsAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $jsOptions = ['position' => View::POS_HEAD];

    public $css = [
    ];

    public $js = [
        'js/humhub.externalWebsites.SpaceSettings.js',
    ];
}
