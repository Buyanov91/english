<?php

use yii\db\Migration;

/**
 * Class m181102_091151_add_alterColumn_default_null
 */
class m181102_091151_add_alterColumn_default_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('text', 'filepath');
        $this->addColumn('text', 'filepath', $this->string()->defaultValue('NULL'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181102_091151_add_alterColumn_default_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181102_091151_add_alterColumn_default_null cannot be reverted.\n";

        return false;
    }
    */
}
