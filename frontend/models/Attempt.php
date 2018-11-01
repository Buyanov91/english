<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attempt".
 *
 * @property int $user_id
 * @property int $infinitive_id
 * @property int $en_to_ru
 * @property int $success
 *
 * @property User $user
 * @property Infinitive $infinitive
 */
class Attempt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attempt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'infinitive_id', 'en_to_ru', 'success'], 'required'],
            [['user_id', 'infinitive_id', 'en_to_ru', 'success'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'en_to_ru' => 'En To Ru',
            'success' => 'Success',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfinitive()
    {
        return $this->hasOne(Infinitive::className(), ['id' => 'infinitive_id']);
    }
}
