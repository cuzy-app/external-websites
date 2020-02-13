<?php
use yii\db\Migration;

class uninstall extends yii\db\Migration
{

    public function up()
    {
        $this->dropTable('iframe_page');
        $this->dropTable('iframe_container_page');
        $this->dropTable('url');
        $this->dropTable('iframe_container_url');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
