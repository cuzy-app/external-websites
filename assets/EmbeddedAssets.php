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
