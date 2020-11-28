<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;

use Yii;
use humhub\modules\comment\models\Comment as CommentModel;
use humhub\modules\comment\permissions\CreateComment;
use humhub\modules\externalWebsites\models\Page;


/**
 * This widget is used to create a new comment form when content is not created
 * 
 */
class FirstCommentForm extends \humhub\modules\comment\widgets\Form
{
    /**
     * humhub\modules\space\models\Space
     */
    public $contentContainer;

    /**
     * object Website id
     */
    public $websiteId;

    /**
     * page URL
     */
    public $pageUrl;

    /**
     * page Title
     */
    public $title;


    /**
     * Executes the widget.
     */
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }

        // As content is not yet created, check permission with space (future content container)
        if (!$this->contentContainer->permissionManager->can(new CreateComment)) {
            return '';
        }

        $this->model = new CommentModel();

        return $this->render('firstCommentForm', [
            'objectModel' => Page::class,
            'model' => $this->model,
            'isNestedComment' => ($this->object instanceof CommentModel),
            'id' => 'first_comment',
            'websiteId' => $this->websiteId,
            'pageUrl' => $this->pageUrl,
            'title' => $this->title,
        ]);
    }

}
