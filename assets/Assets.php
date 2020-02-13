<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://gitlab.com/funkycram/module-humhub-iframe/blob/master/LICENSE
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\assets;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = '@iframe/resources';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'iframe.css?v=0.1',
    ];
    
    public $js = [
    	'iframeResizer.min.js?v=4.2.9', // https://github.com/davidjbradshaw/iframe-resizer/releases
    ];

}
