<?php

use yii\db\Migration;

/**
 * Class m181028_075930_add_tables
 */
class m181028_075930_add_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__ . '/english.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181028_075930_add_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181028_075930_add_tables cannot be reverted.\n";

        return false;
    }
    */
}
