<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "word".
 *
 * @property int $id
 * @property string $word
 * @property int $sentence_id
 * @property int $amount
 *
 * @property Infinitive[] $infinitives
 * @property Sentence $sentence
 */
class Word extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'word';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word', 'sentence_id', 'amount'], 'required'],
            [['sentence_id', 'amount'], 'integer'],
            [['word'], 'string', 'max' => 255],
            [['sentence_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sentence::className(), 'targetAttribute' => ['sentence_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word' => 'Word',
            'sentence_id' => 'Sentence ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfinitives()
    {
        return $this->hasMany(Infinitive::className(), ['word_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentence()
    {
        return $this->hasOne(Sentence::className(), ['id' => 'sentence_id']);
    }

}
