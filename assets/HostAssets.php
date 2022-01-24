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
 * Assests when external website is embedded in Humhub
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
