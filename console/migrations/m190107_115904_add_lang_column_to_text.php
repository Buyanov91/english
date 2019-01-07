<?php

use yii\db\Migration;

/**
 * Class m190107_115904_add_lang_column_to_text
 */
class m190107_115904_add_lang_column_to_text extends Migration
{

    private $tableName = 'text';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'lang', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190107_115904_add_lang_column_to_text cannot be reverted.\n";

        return false;
    }
}
