<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\widgets;

use Yii;
use humhub\modules\comment\permissions\CreateComment;

/**
 * This widget is used to create a new comment form when content is not created
 */
class FirstCommentForm extends \humhub\components\Widget
{
    /**
     * humhub\modules\space\models\Space
     */
    public $space;

    /**
     * object ContainerPage id
     */
    public $containerPageId;

    /**
     * page URL in the iframe
     */
    public $iframeUrl;

    /**
     * page Title in the iframe
     */
    public $iframeTitle;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        /** @var Module $module */
        if (!$this->space->permissionManager->can(new CreateComment)) {
            return '';
        }

        return $this->render('firstCommentForm', [
            'id' => 'first_comment',
            'containerPageId' => $this->containerPageId,
            'iframeUrl' => $this->iframeUrl,
            'iframeTitle' => $this->iframeTitle,
        ]);
    }

}
