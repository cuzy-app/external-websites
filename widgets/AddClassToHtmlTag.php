<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;

use humhub\components\Widget;

/**
 * Adds a class to the html tag to know if Humhub is in an iframe or not
 * This widget is called at the beginning of the body
 * Do not replace this widget with an JS asset as assets load after all HTML has rendered
 */
class AddClassToHtmlTag extends Widget
{
    public function run()
    {
        return $this->render('addClassToHtmlTag', [
        ]);
    }
}
