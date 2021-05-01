<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;

use humhub\components\Widget;

/**
 * IconSelect widget renders a icon selection
 */
class IconSelect extends Widget
{

    public $model;
    
    public function run()
    {
        return $this->render('iconSelect', [
            'model' => $this->model,
        ]);
    }
}
