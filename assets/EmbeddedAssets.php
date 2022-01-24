<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;
use yii\web\View;

/**
 * Assests when Humhub is embedded in the external website
 */
class EmbeddedAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $jsOptions = ['position' => View::POS_END];

    public $css = [
    ];

    public $js = [
        'js/iframeResizer/iframeResizer.contentWindow.min.js?v=4.2.11', // https://github.com/davidjbradshaw/iframe-resizer/releases
    ];
}
