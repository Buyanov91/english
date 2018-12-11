<?php

namespace app\models;

use common\models\User;
use yii\web\UploadedFile;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $firstname
 * @property string $lastname
 * @property int $age
 * @property int $avatar
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'firstname', 'lastname', 'age'], 'required'],
            [['user_id', 'age'], 'integer'],
            [['firstname', 'lastname', 'avatar'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'age' => 'Возраст',
            'avatar' => 'Фото'
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

    public function updateAttributesFromForm(?UploadedFile $avatar): void
    {
        $path = \Yii::$app->params['pathUploads'];
        $this->user_id = \Yii::$app->user->id;
        if(!empty($avatar)){
            $avatar->saveAs($path . $avatar->name);
            $this->avatar = $path . $avatar->name;
        } else {
            if(!empty($this->avatar)){
                unlink($this->avatar);
                $this->avatar = '';
            }
        }
    }
}
