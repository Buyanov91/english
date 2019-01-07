<?php

use yii\db\Migration;

/**
 * Class m190107_121433_change_type_of_column
 */
class m190107_121433_change_type_of_column extends Migration
{

    private $tableName = 'setting';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'lang');
        $this->addColumn($this->tableName, 'lang', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190107_121433_change_type_of_column cannot be reverted.\n";

        return false;
    }

}
