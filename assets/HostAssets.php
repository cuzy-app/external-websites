<?php

/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;

/**
 * Assests when external website is embedded in HumHub
 */
class HostAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $css = [
        'css/humhub.externalWebsites.Host.css',
    ];

    public $js = [
        'js/iframeResizer/iframeResizer.min.js?v=4.2.11', // https://github.com/davidjbradshaw/iframe-resizer/releases
        'js/humhub.externalWebsites.Host.js',
    ];
}
