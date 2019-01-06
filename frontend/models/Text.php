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

    /**
     * @return string
     */
    public function textMD5(): string
    {
        return md5($this->text);
    }

    /**
     * @param string $filename
     */
    public function updateAttributesFromFile(string $filename): void
    {
        $path = Yii::$app->params['pathUploads'].$filename;
        $textFile = file_get_contents($path);

        $this->text = $textFile;
        $this->filepath = $path;
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
    }

    /**
     *
     */
    public function updateAttributesFromForm(): void
    {
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
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
     *
     */
    public function parseText()
    {
        $sentences = explode('. ', trim($this->text));

        foreach ($sentences as $sent) {
            $sentence = new Sentence();
            $sentence->updateAttributesFromText($this->id, $sent);

            $words = self::parseTextToWords($sent);

            foreach ($words as $newWord => $amount) {

                $translate = new Translate($newWord);
                $translate->translate(Translate::ENG_TO_RUS);

                $infinitive = new Infinitive();
                $infinitive->updateAttributesFromWord($translate->infinitive, $translate->translate, $amount);

                $word = new Word();
                $word->updateAttributesFromSentences($newWord, $sentence->id, $infinitive->id);
            }
        }
    }

    /**
     * @param string $text
     * @return array
     */
    public static function parseTextToWords(string $text): array
    {
        $words = [];

        $symbols = array('!',',','.','\'','"','-','–',':',';','?',"\r",'(',')');

        $text = str_replace($symbols, '', $text);

        $text = str_replace("\n", ' ', $text);

        $text_array = explode(' ',$text);

        foreach($text_array as $val){
            if($val==''){continue;}
            $val = strtolower($val);
            if(array_key_exists($val, $words)){
                $words[$val]++;
            } else {
                $words[$val] = 1;
            }
        }

        return $words;
    }

}
