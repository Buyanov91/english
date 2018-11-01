<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "infinitive".
 *
 * @property int $id
 * @property int $word_id
 * @property string $infinitive
 * @property int $amount
 *
 * @property Attempt[] $attempts
 * @property Word $word
 * @property Study[] $studies
 */
class Infinitive extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'infinitive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word_id', 'infinitive', 'amount'], 'required'],
            [['word_id', 'amount'], 'integer'],
            [['infinitive'], 'string', 'max' => 255],
            [['word_id'], 'exist', 'skipOnError' => true, 'targetClass' => Word::className(), 'targetAttribute' => ['word_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word_id' => 'Word ID',
            'infinitive' => 'Infinitive',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttempts()
    {
        return $this->hasMany(Attempt::className(), ['infinitive_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWord()
    {
        return $this->hasOne(Word::className(), ['id' => 'word_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudies()
    {
        return $this->hasMany(Study::className(), ['infinitive_id' => 'id']);
    }
}
