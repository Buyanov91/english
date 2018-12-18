<?php

namespace app\models;

use common\models\User;

/**
 * This is the model class for table "setting".
 *
 * @property int $user_id
 * @property int $attempts
 * @property int $lang
 *
 * @property User $user
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'attempts', 'lang'], 'integer'],
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
            'attempts' => 'Количество вариантов ответа',
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
        $this->save();
    }
}
