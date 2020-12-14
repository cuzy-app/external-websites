<?php

use yii\db\Migration;

/**
 * Class m200212_175551_initial
 */
class m200212_175551_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('external_websites_website', array(
            'id' => $this->primaryKey(),
            'space_id' => $this->integer(11)->notNull(),
            'title' => $this->string(255),
            'icon' => $this->string(100),
            'humhub_is_embedded' => $this->boolean()->defaultValue(true),
            'first_page_url' => $this->text()->notNull(),
            'show_in_menu' => $this->boolean()->defaultValue(false),
            'sort_order' => $this->integer(11)->defaultValue(0),
            'remove_from_url_title' => $this->string(255),
            'hide_sidebar' => $this->boolean()->defaultValue(false),
            'default_content_visibility' => $this->tinyInteger(),
            'default_content_archived' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
        ), '');
        $this->createTable('external_websites_website_page', array(
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'page_url' => $this->text(),
            'website_id' => $this->integer(11)->notNull(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
        ), '');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200212_175551_initial cannot be reverted.\n";

        return false;
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200212_175551_initial cannot be reverted.\n";

        return false;
    }
    */
}
