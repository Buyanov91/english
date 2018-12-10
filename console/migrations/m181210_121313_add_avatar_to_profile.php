<?php

use yii\db\Migration;

/**
 * Class m181210_121313_add_avatar_to_profile
 */
class m181210_121313_add_avatar_to_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('profile', 'avatar', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181210_121313_add_avatar_to_profile cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181210_121313_add_avatar_to_profile cannot be reverted.\n";

        return false;
    }
    */
}
