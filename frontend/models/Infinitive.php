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
    public $id_if_exists;

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

    /**
     * @param string $infinitive
     * @param string $translate
     * @param int $amount
     */
    public function updateAttributesFromWord(string $infinitive, string $translate, int $amount): void
    {
        if(!self::checkInfinitiveExists($this, $infinitive)){
            $this->infinitive = $infinitive;
            $this->translate = $translate;
            $this->amount = $amount;
            $this->save();
        }
    }

    /**
     * @param string $infinitive
     * @param Infinitive $object
     * @return bool
     */
    public static function checkInfinitiveExists(Infinitive $object, string $infinitive) : bool
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
                $object->id = $value->infinitive->id;
                return true;
            }
        }
        return false;
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
            ->andWhere(['study.status' => Study::STATUS_STUDY])
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
            ->andWhere(['study.status' => Study::STATUS_STUDIED])
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
     * @param int $id
     * @param int $count
     * @return array
     */
    public static function findRandomInfinitives(int $id, int $count): array
    {
        $all = self::find()
            ->select('id, infinitive, translate')
            ->where('id!='.$id)
            ->orderBy('RAND()')
            ->limit($count)
            ->asArray()
            ->all();
        return $all;
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
        }

        $countVars = Setting::getCountVars();

        $random = array_rand($words);

        $all = self::findRandomInfinitives($words[$random]['id'], $countVars);

        $all[] = [
            'id' => $words[$random]['id'],
            'infinitive' => $words[$random]['infinitive'],
            'translate' => $words[$random]['translate']
        ];

        shuffle($all);

        $words = [
            'id' => $words[$random]['id'],
            'infinitive' => $words[$random]['infinitive'],
            'translate' => $words[$random]['translate'],
            'mistakes' => $all
        ];

        return $words;
    }

}
