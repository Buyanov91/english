<?php

namespace app\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "text".
 *
 * @property int $id
 * @property string $text
 * @property string $text_md5
 * @property string $filepath
 * @property int $user_id
 *
 * @property Sentence[] $sentences
 * @property User $user
 */
class Text extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'text', 'text_md5'], 'required'],
            [['user_id'], 'integer'],
            [['text', 'text_md5', 'filepath'], 'string', 'max' => 255],
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
            'text' => 'Текст',
            'text_md5' => 'Text Md5',
            'filepath' => 'Filepath',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentences()
    {
        return $this->hasMany(Sentence::className(), ['text_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function textMD5($text)
    {
        return md5($this->text);
    }

}
