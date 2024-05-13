<?php

use humhub\components\Migration;

/**
 * Handles adding columns to table `{{%external_websites_website_page}}`.
 */
class m220303_193035_add_other_website_ids_column_to_external_websites_website_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->safeAddColumn('{{%external_websites_website_page}}', 'other_website_ids', $this->text()->after('website_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220303_193035_add_other_website_ids_column_to_external_websites_website_page_table cannot be reverted.\n";

        return false;
    }
}
