<?php

use yii\db\Migration;

/**
 * Class m181109_082916_add_primary_key_to_study
 */
class m181109_082916_add_primary_key_to_study extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('study', 'id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT primary key FIRST');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181109_082916_add_primary_key_to_study cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181109_082916_add_primary_key_to_study cannot be reverted.\n";

        return false;
    }
    */
}
