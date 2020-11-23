<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\controllers;

use Yii;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\iframe\models\ContainerUrl;
use humhub\modules\comment\models\Comment;

/**
 * AdminController
 * For administrators only
 */
class AdminController extends \humhub\modules\admin\components\Controller
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            ['permissions' => ManageSettings::class]
        ];
    }

    /**
     * /iframe/admin/delete-all-contents-without-comments
     */
    public function actionDeleteAllContentsWithoutComments ()
    {
        foreach (ContainerUrl::find()->all() as $containerUrl) {
            $content = $containerUrl->content;
            $comment = Comment::find()
                ->where(['object_id' => $content['object_id']])
                ->andWhere(['object_model' => $content['object_model']])
                ->one();
            if ($comment === null) {
                $containerUrl->delete();
            }
        }
        return 'done';
    }
}
