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
        foreach (Website::find()->all() as $website) {
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_145036_remove_fa_from_icon_values cannot be reverted.\n";

        return false;
    }
    */
}
