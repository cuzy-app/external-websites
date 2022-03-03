<?php

use yii\db\Migration;

/**
 * Class m220303_210819_add_page_url_params_to_remove_to_external_websites_website_table
 */
class m220303_210819_add_page_url_params_to_remove_to_external_websites_website_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%external_websites_website}}', 'page_url_params_to_remove', $this->text()->after('first_page_url'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220303_210819_add_page_url_params_to_remove_to_external_websites_website_table cannot be reverted.\n";

        return false;
    }
}
