<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\widgets;


/**
 * WallEntry is used to display iframe content inside the stream.
 */
class WallEntry extends \humhub\modules\content\widgets\WallEntry
{
    public function run()
    {
        return $this->render('wallEntry', [
            'containerUrl' => $this->contentObject,
            'space' => $this->contentObject->content->container
        ]);
    }

}
