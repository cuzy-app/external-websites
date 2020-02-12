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
    // public function safeUp()
    // {

    // }

    /**
     * {@inheritdoc}
     */
    // public function safeDown()
    // {
    //     echo "m200212_175551_initial cannot be reverted.\n";

    //     return false;
    // }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('iframe_page', array(
            'id' => 'pk',
            'title' => 'varchar(255) NOT NULL',
            'icon' => 'varchar(100)',
            'page_url' => 'text DEFAULT NULL',
            'iframe_url' => 'text DEFAULT NULL',
            'target' => 'varchar(255)',
            'sort_order' => 'int(11)',
            'state' => 'tinyint NOT NULL DEFAULT 0',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('iframe_container_page', array(
            'id' => 'pk',
            'space_id' => 'int(11) NOT NULL DEFAULT 0',
            'title' => 'varchar(255) NOT NULL',
            'icon' => 'varchar(100)',
            'page_url' => 'text DEFAULT NULL',
            'iframe_url' => 'text DEFAULT NULL',
            'sort_order' => 'int(11)',
            'state' => 'tinyint NOT NULL DEFAULT 0',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('iframe_url', array(
            'id' => 'pk',
            'url' => 'text DEFAULT NULL',
            'iframe_page_id' => 'int(11) NOT NULL DEFAULT 0',
            'content_id' => 'int(11) NOT NULL DEFAULT 0',
            'state' => 'tinyint NOT NULL DEFAULT 0',
            'comments_state' => 'tinyint NOT NULL DEFAULT 0',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('iframe_container_url', array(
            'id' => 'pk',
            'url' => 'text DEFAULT NULL',
            'iframe_container_page_id' => 'int(11) NOT NULL DEFAULT 0',
            'content_id' => 'int(11) NOT NULL DEFAULT 0',
            'state' => 'tinyint NOT NULL DEFAULT 0',
            'comments_state' => 'tinyint NOT NULL DEFAULT 0',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
    }

    public function down()
    {
        echo "m190609_090436_initial cannot be reverted.\n";
        $this->dropTable('iframe_page');
        $this->dropTable('iframe_container_page');
        $this->dropTable('iframe_url');
        $this->dropTable('iframe_container_url');
        return false;
    }
}
