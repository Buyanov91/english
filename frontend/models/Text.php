<?php

namespace app\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "text".
 *
 * @property int $id
 * @property string $text
 * @property string $text_md5
 * @property string $filepath
 * @property int $user_id
 * @property string $lang
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
            [['text', 'text_md5', 'filepath', 'lang'], 'string', 'max' => 255],
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
            'lang' => 'Язык перевода',
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

    /**
     * @return string
     */
    public function textMD5(): string
    {
        return md5($this->text);
    }

    /**
     *
     */
    public function setLang(): void
    {
        $this->lang = Setting::getLang();
    }

    /**
     * @param string $filename
     */
    public function updateAttributesFromFile(string $filename): void
    {
        $path = Yii::$app->params['pathUploads'].$filename;
        $textFile = file_get_contents($path);

        $this->text = trim($textFile);
        $this->filepath = $path;
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
        $this->setLang();
    }

    /**
     *
     */
    public function updateAttributesFromForm(): void
    {
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
        $this->setLang();
    }

    /**
     * @param string $text
     * @return bool
     */
    public static function checkTextForExist(string $text): bool
    {
        $texts = self::find()
            ->select('text')
            ->where('user_id = '.Yii::$app->user->id)
            ->asArray()
            ->all();

        $arr = [];

        foreach ($texts as $id => $value){
            $arr[] = $value['text'];
        }

        if(array_search($text, $arr)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public static function findAllWords(): array
    {
        $words = self::find()
            ->select('*')
            ->join('JOIN', 'sentence', 'sentence.text_id=text.id')
            ->join('JOIN', 'word', 'word.sentence_id=sentence.id')
            ->join('JOIN', 'infinitive', 'word.infinitive_id=infinitive.id')
            ->join('LEFT JOIN', 'study', 'study.infinitive_id=infinitive.id')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->andWhere(['text.lang' => Setting::getLang()])
            ->asArray()
            ->limit(20)
            ->all();
        return $words;
    }
}
