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
            'title' => 'varchar(255) DEFAULT NULL',
            'icon' => 'varchar(100) DEFAULT NULL',
            'start_url' => 'text NOT NULL',
            'target' => 'varchar(100) DEFAULT NULL',
            'sort_order' => 'int(11) DEFAULT 0',
            'state' => 'varchar(100) DEFAULT NULL',
            'comments_global_state' => 'varchar(100) DEFAULT NULL',
            'visibility' => 'tinyint(4) DEFAULT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('url', array(
            'id' => 'pk',
            'url' => 'text NOT NULL',
            'page_id' => 'int(11) NOT NULL',
            'comments_state' => 'varchar(100) DEFAULT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('iframe_container_page', array(
            'id' => 'pk',
            'space_id' => 'int(11) NOT NULL',
            'title' => 'varchar(255) DEFAULT NULL',
            'icon' => 'varchar(100) DEFAULT NULL',
            'start_url' => 'text NOT NULL',
            'target' => 'varchar(100) DEFAULT NULL',
            'sort_order' => 'int(11) DEFAULT 0',
            'state' => 'varchar(100) DEFAULT NULL',
            'comments_global_state' => 'varchar(100) DEFAULT NULL',
            'visibility' => 'tinyint(4) DEFAULT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        $this->createTable('iframe_container_url', array(
            'id' => 'pk',
            'url' => 'text DEFAULT NULL',
            'container_page_id' => 'int(11) NOT NULL',
            'comments_state' => 'varchar(100) DEFAULT NULL',
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
        $this->dropTable('url');
        $this->dropTable('iframe_container_url');
        return false;
    }
}
