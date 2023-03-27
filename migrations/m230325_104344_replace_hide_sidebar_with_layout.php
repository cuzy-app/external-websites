<?php

use humhub\modules\externalWebsites\models\Website;
use yii\db\Migration;

/**
 * Class m230325_104344_replace_hide_sidebar_with_layout
 */
class m230325_104344_replace_hide_sidebar_with_layout extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%external_websites_website}}', 'hide_sidebar');
        $this->addColumn('{{%external_websites_website}}', 'layout', $this->string(127)->after('sort_order'));

        /** @var Website $website */
        foreach (Website::find()->each() as $website) {
            $website->layout = Website::LAYOUT_FULL_SCREEN;
            $website->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230325_104344_replace_hide_sidebar_with_layout cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230325_104344_replace_hide_sidebar_with_layout cannot be reverted.\n";

        return false;
    }
    */
}
