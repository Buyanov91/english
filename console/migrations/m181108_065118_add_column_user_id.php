<?php

use yii\db\Migration;

/**
 * Class m181108_065118_add_column_user_id
 */
class m181108_065118_add_column_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('infinitive', 'user_id', $this->integer());
        $this->addForeignKey('infinitive_to_user', 'infinitive', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181108_065118_add_column_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181108_065118_add_column_user_id cannot be reverted.\n";

        return false;
    }
    */
}
