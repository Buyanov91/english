<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "infinitive".
 *
 * @property int $id
 * @property string $infinitive
 * @property string $translate
 * @property int $amount
 *
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
            [['infinitive', 'amount'], 'required'],
            [['amount'], 'integer'],
            [['infinitive', 'translate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWords()
    {
        return $this->hasMany(Word::className(), ['infinitive_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudy()
    {
        return $this->hasMany(Study::className(), ['infinitive_id' => 'id']);
    }

    public function updateAttributesFromWord(string $infinitive, int $amount)
    {
        $check = self::checkInfinitiveExists($infinitive);
        if($check === null){
            $this->infinitive = $infinitive;
            $this->amount = $amount;
            $this->save();
        }
        return $check;
    }

    /**
     * @param string $infinitive
     * @return Infinitive|null
     */
    public static function checkInfinitiveExists(string $infinitive) : ?Infinitive
    {
        $infinitives = Word::find()
            ->innerJoinWith('infinitive')
            ->innerJoinWith('text')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->groupBy('infinitive.id')
            ->all();
        foreach ($infinitives as $value){
            if($infinitive === $value->infinitive->infinitive){

                return self::updateAmount($value->infinitive->id);
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return Infinitive
     */
    private static function updateAmount(int $id) : Infinitive
    {
        $infinitive = self::findOne($id);
        $infinitive->updateCounters(['amount' => 1]);
        return $infinitive;
    }

    /**
     * @return array
     */
    public static function findInfinitivesToStudy(): array
    {
        $infinitives = self::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 1])
            ->asArray()
            ->all();
        return $infinitives;
    }

    /**
     * @return float
     */
    public static function calcPercentStudiedWords(): float
    {
        $count_study = self::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 2])
            ->count();

        $count_all = self::find()->count();
        if($count_all != 0) {
            $percent = round($count_study/$count_all*100, 1);
        } else {
            $percent = 0;
        }


        return $percent;
    }

    /**
     * @return array
     */
    public static function getRandomWordForJson(): array
    {
        $words = self::findInfinitivesToStudy();

        if(empty($words)) {
            $words = [''];
            return $words;
        } else {
            $random = array_rand($words);

            $translate = new Translate($words[$random]['infinitive']);
            $translate->translate(Translate::ENG_TO_RUS);

            $words = [
                'id' => $words[$random]['id'],
                'infinitive' => $words[$random]['infinitive'],
                'translate' => $translate->translate
            ];

            return $words;
        }
    }
}
