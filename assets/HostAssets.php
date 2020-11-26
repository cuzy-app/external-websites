<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\assets;

use humhub\components\assets\AssetBundle;

class HostAssets extends AssetBundle
{
    public $sourcePath = '@external-websites/resources';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'css/humhub.externalWebsites.Host.css',
    ];
    
    public $js = [
    	'js/iframeResizer/iframeResizer.min.js?v=4.2.11', // https://github.com/davidjbradshaw/iframe-resizer/releases
        'js/humhub.externalWebsites.Host.js',
    ];
}
