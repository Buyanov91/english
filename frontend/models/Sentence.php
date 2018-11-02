<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sentence".
 *
 * @property int $id
 * @property int $text_id
 * @property string $sentence
 *
 * @property Text $text
 * @property Word[] $words
 */
class Sentence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sentence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text_id', 'sentence'], 'required'],
            [['text_id'], 'integer'],
            [['sentence'], 'string', 'max' => 255],
            [['text_id'], 'exist', 'skipOnError' => true, 'targetClass' => Text::className(), 'targetAttribute' => ['text_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text_id' => 'Text ID',
            'sentence' => 'Sentence',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasOne(Text::className(), ['id' => 'text_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWords()
    {
        return $this->hasMany(Word::className(), ['sentence_id' => 'id']);
    }

}
