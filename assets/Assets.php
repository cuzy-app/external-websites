<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
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
        'module.css?v=0.4',
    ];
    
    public $js = [
    	'iframeResizer.min.js?v=4.2.9', // https://github.com/davidjbradshaw/iframe-resizer/releases
        'module.js?v=0.2',
    ];

}
