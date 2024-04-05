<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content\components\ContentContainerControllerAccess;
use humhub\modules\externalWebsites\models\forms\SpaceSettingsForm;
use humhub\modules\externalWebsites\models\forms\WebsiteForm;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\WebsiteSearch;
use humhub\modules\space\models\Space;
use Yii;


class ManageController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            [ContentContainerControllerAccess::RULE_USER_GROUP_ONLY => [Space::USERGROUP_ADMIN]],
        ];
    }

    public function actionWebsites()
    {
        // Get data provider
        $searchModel = new WebsiteSearch(['space_id' => $this->contentContainer->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('websites', [
            'contentContainer' => $this->contentContainer,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAddWebsite()
    {
        $model = new WebsiteForm(['space_id' => $this->contentContainer->id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->view->success(Yii::t('ExternalWebsitesModule.base', 'Website added'));
            } else {
                $this->view->error(Yii::t('ExternalWebsitesModule.base', 'Error: website not added'));
            }
            return $this->redirect($this->contentContainer->createUrl('/external-websites/manage/websites'));
        }

        return $this->renderAjax('add-website', [
            'model' => $model,
            'contentContainer' => $this->contentContainer,
        ]);
    }


    public function actionEditWebsite($websiteId)
    {
        $model = new WebsiteForm([
            'space_id' => $this->contentContainer->id,
            'id' => $websiteId,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->view->success(Yii::t('ExternalWebsitesModule.base', 'Website updated'));
            } else {
                $this->view->error(Yii::t('ExternalWebsitesModule.base', 'Error: website not updated'));
            }
            return $this->redirect($this->contentContainer->createUrl('/external-websites/manage/websites'));
        }

        return $this->renderAjax('edit-website', [
            'model' => $model,
            'contentContainer' => $this->contentContainer,
        ]);
    }


    public function actionDeleteWebsite($websiteId)
    {
        $website = Website::findOne($websiteId);
        if ($website === null) {
            throw new HttpException(404);
        }

        $website->delete();
        $this->view->success(Yii::t('ExternalWebsitesModule.base', 'website deleted'));

        return $this->redirect($this->contentContainer->createUrl('/external-websites/manage/websites'));
    }


    public function actionSpaceSettings()
    {
        $model = new SpaceSettingsForm(['contentContainer' => $this->contentContainer]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->view->saved();
            } else {
                $this->view->error('Error');
            }
            return $this->redirect($this->contentContainer->createUrl('/external-websites/manage/websites'));
        }

        return $this->renderAjax('spaceSettings', [
            'model' => $model,
        ]);
    }
}
