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
    public function getText()
    {
        return $this->hasOne(Text::className(), ['id' => 'text_id'])
            ->viaTable(Sentence::tableName(), ['id' => 'sentence_id']);
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
            ->viaTable(Infinitive::tableName(), ['id' => 'infinitive_id']);
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
            ->andWhere(['text.lang' => Setting::getLang()])
            ->groupBy('infinitive.id')
            ->orderBy('infinitive.amount DESC')
            ->limit(20)
            ->all();
        return $infinitives;
    }

    public static function findStudiedWords(): array
    {
        $words = self::find()
            ->select('*')
            ->innerJoinWith('study')
            ->innerJoinWith('text')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->andWhere(['text.lang' => Setting::getLang()])
            ->andWhere(['study.status' => Study::STATUS_STUDIED])
            ->groupBy('infinitive.id')
            ->asArray()
            ->limit(20)
            ->all();
        return $words;
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
            ->andWhere(['text.lang' => Setting::getLang()])
            ->orderBy('infinitive.infinitive')
            ->groupBy('infinitive.id')
            ->asArray()
            ->all();
        return $words;
    }

    /**
     * @return array
     */
    public static function findWordsToStudy(): array
    {
        $words = self::find()
            ->select('*')
            ->innerJoinWith('study')
            ->groupBy('word.word')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => Study::STATUS_STUDY])
            ->asArray()
            ->all();
        return $words;
    }

    /**
     * @return float
     */
    public static function calcPercentStudiedWords(): float
    {
        $count_study = self::find()
            ->innerJoinWith('study')
            ->innerJoinWith('text')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => Study::STATUS_STUDIED])
            ->andWhere(['text.lang' => Setting::getLang()])
            ->count();

        $count_all = self::find()
            ->innerJoinWith('text')
            ->innerJoinWith('infinitive')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->andWhere(['text.lang' => Setting::getLang()])
            ->groupBy('infinitive.id')
            ->count();

        $percent = 0;

        if((int) $count_all !== 0) {
            if ((int) $count_study !== 0) {
                $percent = round($count_study/$count_all*100, 1);
            }
        }
        return $percent;
    }

    /**
     * @return array
     */
    public static function getRandomWordForJson(): array
    {
        $words = self::findWordsToStudy();

        if(empty($words)) {
            $words = [''];
            return $words;
        }

        $countVars = Setting::getCountVars();

        $random = array_rand($words);

        $all = self::findRandomWords($words[$random]['infinitive_id'], $countVars);
        $all = self::prepareArray($all);

        $all[] = [
            'id' => $words[$random]['infinitive_id'],
            'infinitive' => $words[$random]['infinitive'],
            'translate' => $words[$random]['translate']
        ];

        shuffle($all);

        $words = [
            'id' => $words[$random]['infinitive_id'],
            'infinitive' => $words[$random]['infinitive'],
            'translate' => $words[$random]['translate'],
            'mistakes' => $all
        ];

        return $words;
    }

    /**
     * @param int $id
     * @param int $count
     * @return array
     */
    private static function findRandomWords(int $id, int $count): array
    {
        $all = self::find()
            ->innerJoinWith('infinitive')
            ->innerJoinWith('text')
            ->where('infinitive.id!='.$id)
            ->andWhere(['text.lang' => Setting::getLang()])
            ->groupBy('word.word')
            ->orderBy('RAND()')
            ->limit($count-1)
            ->asArray()
            ->all();
        return $all;
    }

    private static function prepareArray($arr)
    {
        $all = [];
        foreach ($arr as $a) {
            $all[] = $a['infinitive'];
        }
        return $all;
    }

}
