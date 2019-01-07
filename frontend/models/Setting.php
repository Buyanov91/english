<?php

namespace app\models;

use common\models\User;

/**
 * This is the model class for table "setting".
 *
 * @property int $user_id
 * @property int $attempts
 * @property string $lang
 *
 * @property User $user
 */
class Setting extends \yii\db\ActiveRecord
{
    const ENG_TO_RUS = 'en-ru';

    const RUS_TO_ENG = 'ru-en';

    const DEFAULT_ATTEMPTS = 3;

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
            [['user_id', 'attempts'], 'integer'],
            [['lang'], 'string'],
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

    /**
     * @return array|string[]
     */
    public static function primaryKey()
    {
        return ['user_id'];
    }

    /**
     *
     */
    public function updateAttributesFromForm(): void
    {
        $this->user_id = \Yii::$app->user->id;
        $this->save();
    }

    /**
     * @return int
     */
    public static function getCountVars(): int
    {
        $setting = self::find()->where(['user_id' => \Yii::$app->user->id])->limit(1)->one();
        if (empty($setting->attempts)) {
            return self::DEFAULT_ATTEMPTS;
        }
        return $setting->attempts;
    }

    /**
     * @return mixed|string
     */
    public static function getLang(): string
    {
        $setting = self::find()->where(['user_id' => \Yii::$app->user->id])->limit(1)->one();
        if (empty($setting->lang)) {
            return self::ENG_TO_RUS;
        }
        return $setting->lang;
    }

}
