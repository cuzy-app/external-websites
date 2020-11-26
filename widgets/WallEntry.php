<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;


/**
 * WallEntry is used to display page content addons inside the stream.
 */
class WallEntry extends \humhub\modules\content\widgets\WallEntry
{
    public function run()
    {
        return $this->render('wallEntry', [
            'page' => $this->contentObject,
            'space' => $this->contentObject->content->container
        ]);
    }

}
