<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;


/**
 * WallStreamEntryWidget is used to display page content addons inside the stream.
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    public function renderContent()
    {
        return $this->render('wallEntry', [
            'page' => $this->model,
            'space' => $this->model->content->container
        ]);
    }

    protected function getTitle()
    {
        return $this->model->website->title;
    }
}
