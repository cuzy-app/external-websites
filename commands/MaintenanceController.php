<?php
/**
 * This controller is only for admin to make modifications in the DB.
 *
 * @author Marc Farré
 */

namespace humhub\modules\jdn\commands;

use humhub\modules\user\models\User;

class MaintenanceController extends \yii\console\Controller
{
    /**
     * php yii external-websites/maintenance/update-yes-wiki-profiles
     */
    public function actionUpdateYesWikiProfiles()
    {
        $i = 0;
        foreach(User::findAll(['status' => User::STATUS_ENABLED]) as $userNotExtended) {
            $i++;
            if ($i > 1000) {
                $i = 0;
                print '.';
            }
        }
        print 'done';
    }
}