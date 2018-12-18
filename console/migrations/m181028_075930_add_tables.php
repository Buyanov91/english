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
        $this->createTable('text', [
            'id' => $this->primaryKey(),
            'text' => $this->text(),
            'text_md5' => $this->string(),
            'filepath' => $this->string(),
            'user_id' => $this->integer()
        ]);
        $this->createTable('sentence', [
            'id' => $this->primaryKey(),
            'text_id' => $this->integer(),
            'sentence' => $this->string()
        ]);
        $this->createTable('word', [
            'id' => $this->primaryKey(),
            'sentence_id' => $this->integer(),
            'infinitive_id' => $this->integer(),
            'word' => $this->string()
        ]);
        $this->createTable('infinitive', [
            'id' => $this->primaryKey(),
            'infinitive' => $this->string(),
            'translate' => $this->string(),
            'amount' => $this->integer()
        ]);
        $this->createTable('study', [
            'user_id' => $this->integer(),
            'infinitive_id' => $this->integer(),
            'status' => $this->integer()->defaultValue(0)
        ]);
        $this->createTable('setting', [
            'user_id' => $this->integer(),
            'attempts' => $this->integer()->defaultValue(2),
            'lang' => $this->integer()
        ]);
        $this->createTable('profile', [
            'user_id' => $this->integer(),
            'firstname' => $this->string(),
            'lastname' => $this->string(),
            'age' => $this->integer(),
            'avatar' => $this->string()
        ]);

        $this->addForeignKey('text_to_user','text', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('profile_to_user','profile', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('setting_to_user','setting', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('study_to_user','study', 'user_id', 'user', 'id', 'CASCADE');

        $this->addForeignKey('sentence_to_text','sentence', 'text_id', 'text', 'id', 'CASCADE');
        $this->addForeignKey('word_to_sentence','word', 'sentence_id', 'sentence', 'id', 'CASCADE');
        $this->addForeignKey('word_to_infinitive','word', 'infinitive_id', 'infinitive', 'id', 'CASCADE');
        $this->addForeignKey('study_to_infinitive','study', 'infinitive_id', 'infinitive', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181028_075930_add_tables cannot be reverted.\n";

        return false;
    }

}
