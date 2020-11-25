<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\widgets;

use Yii;
use humhub\modules\comment\models\Comment as CommentModel;
use humhub\modules\comment\permissions\CreateComment;
use humhub\modules\iframe\models\ContainerUrl;


/**
 * This widget is used to create a new comment form when content is not created
 * 
 */
class FirstCommentForm extends \humhub\modules\comment\widgets\Form
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

        // As content is not yet created, check permission with space (future content container)
        if (!$this->space->permissionManager->can(new CreateComment)) {
            return '';
        }

        $this->model = new CommentModel();

        return $this->render('firstCommentForm', [
            'objectModel' => ContainerUrl::class,
            'model' => $this->model,
            'isNestedComment' => ($this->object instanceof CommentModel),
            'id' => 'first_comment',
            'containerPageId' => $this->containerPageId,
            'iframeUrl' => $this->iframeUrl,
            'iframeTitle' => $this->iframeTitle,
        ]);
    }

}
