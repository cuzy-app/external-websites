<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\widgets;

use humhub\modules\comment\models\Comment as CommentModel;
use humhub\modules\comment\permissions\CreateComment;
use humhub\modules\comment\widgets\Form;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\file\handler\FileHandlerCollection;
use Yii;

/**
 * This widget is used to create a new comment form when content is not created
 *
 */
class FirstCommentForm extends Form
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
        if (!$this->contentContainer->permissionManager->can(CreateComment::class)) {
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
            'fileHandlers' => FileHandlerCollection::getByType([FileHandlerCollection::TYPE_IMPORT, FileHandlerCollection::TYPE_CREATE]),
        ]);
    }

}
