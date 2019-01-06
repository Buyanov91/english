<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "study".
 *
 * @property int $user_id
 * @property int $infinitive_id
 * @property int $status
 *
 * @property Infinitive $infinitive
 */
class Study extends \yii\db\ActiveRecord
{
    const STATUS_STUDY = 1;
    const STATUS_STUDIED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'study';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'infinitive_id', 'status'], 'required'],
            [['user_id', 'infinitive_id', 'status'], 'integer'],
            [['infinitive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Infinitive::className(), 'targetAttribute' => ['infinitive_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'infinitive_id' => 'Infinitive ID',
            'status' => 'Status',
        ];
    }

    public static function primaryKey()
    {
        return ['infinitive_id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfinitive()
    {
        return $this->hasOne(Infinitive::className(), ['id' => 'infinitive_id']);
    }

    /**
     * @param int $word_id
     */
    public function addToStudy(int $word_id): void
    {
        $infinitive_id = Word::find()
            ->innerJoinWith('infinitive')
            ->where(['word.id' => $word_id])
            ->asArray()
            ->one();

        $this->user_id = Yii::$app->user->id;
        $this->infinitive_id = $infinitive_id['infinitive_id'];
        $this->status = Study::STATUS_STUDY;
        $this->save();
    }

    /**
     * @param int $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function removeFromStudy(int $id): void
    {
        $study = self::find()
            ->where(['infinitive_id' => $id])
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->andWhere(['status' => 1])
            ->limit(1)
            ->one();

        $study->status = self::STATUS_STUDIED;
        $study->update();
    }

}
