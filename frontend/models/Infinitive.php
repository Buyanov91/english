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
            [['infinitive', 'amount', 'user_id'], 'required'],
            [['amount'], 'integer'],
            [['infinitive'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'USER',
            'infinitive' => 'Infinitive',
            'amount' => 'Amount',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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

    public static function checkInfinitive($infinitive)
    {
        $infinitives = Infinitive::find()
            ->where('user_id = '.Yii::$app->user->id)
            ->orderBy('id')
            ->asArray()
            ->all();

        foreach ($infinitives as $value){
            if($infinitive === $value['infinitive']){
                return $value['id'];
            } else {
                continue;
            }
        }
    }

    public static function findInfinitivesToStudy()
    {
        $infinitives = self::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 1])
            ->asArray()
            ->all();
        return $infinitives;
    }

    public static function calcPercentStudiedWords()
    {
        $count_study = self::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 2])
            ->count();

        $count_all = self::find()->count();

        $percent = round($count_study/$count_all*100, 1);

        return $percent;
    }
}
