<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\externalWebsites\models\Page;
use humhub\modules\comment\models\Comment;

/**
 * AdminController
 * For administrators only
 */
class AdminController extends Controller
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
     * Hidden page. Access with this URL:
     * /external-websites/admin/delete-all-contents-without-comments
     */
    // public function actionDeleteAllContentsWithoutComments ()
    // {
    //     foreach (Page::find()->all() as $page) {
    //         $content = $page->content;
    //         $comment = Comment::find()
    //             ->where(['object_id' => $content['object_id']])
    //             ->andWhere(['object_model' => $content['object_model']])
    //             ->one();
    //         if ($comment === null) {
    //             $page->delete();
    //         }
    //     }
    //     return 'done';
    // }


    /**
     * Hidden page. Access with this URL:
     * /external-websites/admin/generate-secret-key
     */
    public function actionGenerateSecretKey()
    {
        echo Yii::$app->security->generateRandomString(86);
    }
}
