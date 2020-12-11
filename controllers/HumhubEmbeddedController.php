<?php


namespace humhub\modules\externalWebsites\controllers;

/**
 * Class HumhubEmbeddedController
 * @package humhub\modules\externalWebsites\controllers
 * Called by external website if Humhub is embedded
 */
class HumhubEmbeddedController extends \humhub\components\Controller
{
    public function actionRedirect()
    {
        /**
         * @inheritDoc
         */
        public function beforeAction($action)
        {
            Yii::$app->session->set('humhubIsEmbeded', true);
        }

        $url = Yii::$app->request->get('$humhub_url');
        if (empty($url)) {
            $url = Url::home();
        }

        return $this->redirect($url);
    }
}