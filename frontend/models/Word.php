<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "word".
 *
 * @property int $id
 * @property string $word
 * @property int $sentence_id
 * @property int $infinitive_id
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
            [['word', 'sentence_id', 'infinitive_id'], 'required'],
            [['sentence_id', 'infinitive_id'], 'integer'],
            [['word'], 'string', 'max' => 255],
            [['sentence_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sentence::className(), 'targetAttribute' => ['sentence_id' => 'id']],
            [['infinitive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Infinitive::className(), 'targetAttribute' => ['infinitive_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfinitive()
    {
        return $this->hasOne(Infinitive::className(), ['id' => 'infinitive_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudy()
    {
        return $this->hasOne(Study::className(), ['infinitive_id' => 'id'])
            ->viaTable('infinitive', ['id' => 'infinitive_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentence()
    {
        return $this->hasOne(Sentence::className(), ['id' => 'sentence_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasOne(Text::className(), ['id' => 'text_id'])
            ->viaTable('sentence', ['id' => 'sentence_id']);
    }

    /**
     * @param string $word
     * @param int $infinitive_id
     * @param int $sentence_id
     */
    public function updateAttributesFromSentences(string $word, int $sentence_id, int $infinitive_id): void
    {
        $this->word = $word;
        $this->sentence_id = $sentence_id;
        $this->infinitive_id = $infinitive_id;
        $this->save();
    }

    /**
     * @return array
     */
    public static function findPopular(): array
    {
        $infinitives = self::find()
            ->innerJoinWith('infinitive')
            ->innerJoinWith('text')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->groupBy('infinitive.id')
            ->orderBy('infinitive.amount DESC')
            ->limit(20)
            ->all();
        return $infinitives;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findToAdd(): array
    {
        $words = self::find()
            ->select('word.*, sentence.sentence')
            ->innerJoinWith('infinitive')
            ->innerJoinWith('text')
            ->with('study')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->orderBy('infinitive.infinitive')
            ->groupBy('infinitive.id')
            ->asArray()
            ->all();
        return $words;
    }

}
