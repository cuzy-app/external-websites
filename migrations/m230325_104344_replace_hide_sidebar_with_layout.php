<?php

use humhub\components\Migration;
use humhub\modules\externalWebsites\models\Website;

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
        $this->safeDropColumn('{{%external_websites_website}}', 'hide_sidebar');
        $this->safeAddColumn('{{%external_websites_website}}', 'layout', $this->string(127)->after('sort_order'));

        /** @var Website $website */
        foreach (Website::find()->each() as $website) {
            $website->layout = Website::LAYOUT_DEFAULT;
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
}
