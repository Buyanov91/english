<?php

use yii\db\Migration;

/**
 * Class m181108_062053_drop_column_add_column
 */
class m181108_062053_drop_column_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('infinitive_ibfk_1', 'infinitive');
        $this->dropColumn('infinitive', 'word_id');
        $this->addColumn('word', 'infinitive_id', $this->integer());
        $this->addForeignKey('word_to_infinitive', 'word', 'infinitive_id', 'infinitive', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181108_062053_drop_column_add_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181108_062053_drop_column_add_column cannot be reverted.\n";

        return false;
    }
    */
}
