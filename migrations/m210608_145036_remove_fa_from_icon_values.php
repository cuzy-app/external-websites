<?php

use humhub\modules\externalWebsites\models\Website;
use yii\db\Migration;

/**
 * Class m210608_145036_remove_fa_from_icon_values
 */
class m210608_145036_remove_fa_from_icon_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (Website::find()->each() as $website) {
            $website->icon = str_replace('fa-', '', $website->icon);
            $website->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210608_145036_remove_fa_from_icon_values cannot be reverted.\n";

        return false;
    }
}
