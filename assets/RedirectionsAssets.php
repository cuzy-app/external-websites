<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;

/**
 * Class EmbeddedAssets
 * @package humhub\modules\externalWebsites\assets
 * Assests to redirect URLs to an external website
 */
class RedirectionsAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $publishOptions = ['forceCopy' => false];

    public $css = [
    ];

    public $js = [
        'js/humhub.externalWebsites.Redirections.js',
    ];
}
