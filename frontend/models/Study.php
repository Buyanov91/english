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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfinitive()
    {
        return $this->hasOne(Infinitive::className(), ['id' => 'infinitive_id']);
    }
}
