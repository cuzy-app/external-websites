<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use humhub\modules\admin\components\Controller;
use Yii;

/**
 * AdminController
 * For administrators only
 */
class AdminController extends Controller
{
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
