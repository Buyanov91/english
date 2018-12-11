<?php

namespace app\models;

use common\models\User;

/**
 * This is the model class for table "settings".
 *
 * @property int $user_id
 * @property int $attempts_to_study
 * @property int $lang
 *
 * @property User $user
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'attempts_to_study', 'lang'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'attempts_to_study' => 'Число попыток на перевод',
            'lang' => 'Язык перевода'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }


    public function updateAttributesFromForm(): void
    {
        $this->user_id = \Yii::$app->user->id;
    }
}
