<?php

namespace app\models;

use Yii;
use common\models\User;

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
            [['infinitive', 'amount'], 'required'],
            [['amount'], 'integer'],
            [['infinitive'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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

    public function updateAttributesFromWord(string $infinitive, int $amount): void
    {
        if(!self::checkInfinitiveExists($infinitive)){
            $this->infinitive = $infinitive;
            $this->amount = $amount;
            $this->save();
        }
    }

    /**
     * @param string $infinitive
     * @return bool
     */
    public static function checkInfinitiveExists(string $infinitive): bool
    {
        $infinitives = Word::find()
            ->innerJoinWith('infinitive')
            ->innerJoinWith('text')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->groupBy('infinitive.id')
            ->all();
        foreach ($infinitives as $value){
            if($infinitive === $value->infinitive->infinitive){
                self::updateAmount($value->infinitive->id);
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $id
     */
    private static function updateAmount(int $id):void
    {
        $infinitive = self::findOne($id);
        $infinitive->updateCounters(['amount' => 1]);
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
