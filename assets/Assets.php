<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\assets;

use humhub\components\assets\AssetBundle; // not yii\web\AssetBundle for deferred script loading - see https://docs.humhub.org/docs/develop/modules-migrate/#asset-management - Needs Humhub 1.5+

class Assets extends AssetBundle
{
    public $sourcePath = '@iframe/resources';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'module.css?v1.3',
    ];
    
    public $js = [
    	'iframeResizer.min.js?v=4.2.10', // https://github.com/davidjbradshaw/iframe-resizer/releases
        'module.js?v=0.2',
    ];

}
