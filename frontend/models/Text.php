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

    public function textMD5()
    {
        return md5($this->text);
    }

    public function updateAttributesFromFile($filename)
    {
        $path = Yii::$app->params['pathUploads'].$filename;
        $textFile = file_get_contents($path);

        $this->text = $textFile;
        $this->filepath = $path;
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
    }

    public function updateAttributesFromForm()
    {
        $this->text_md5 = $this->textMD5();
        $this->user_id = Yii::$app->user->id;
    }

    public static function checkTextForExist($text)
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

    public function parseText()
    {
        $text = $this->text;
        $text_id = $this->id;

        $sentences = explode('.', $text);

        foreach ($sentences as $sent) {
            $sentence = new Sentence();
            $sentence->sentence = $sent;
            $sentence->text_id = $text_id;
            $sentence->save();

            $words = Text::parseTextToWords($sent);

            foreach ($words as $newWord => $amount) {

                $infinitive_trans = Translate::translate($newWord, Translate::ENG_TO_ENG);
                $infinitive_translate = $infinitive_trans[$newWord]['def'][0]['text'];

                $word = new Word();
                $word->sentence_id = $sentence->id;
                $word->word = $newWord;
                $word->amount = $amount;

                $id = Infinitive::checkInfinitive($infinitive_translate);

                if($id) {
                    $infinitive = Infinitive::findOne($id);
                    $infinitive->updateCounters(['amount' => 1]);
                } else {
                    $infinitive = new Infinitive();
                    $infinitive->infinitive = $infinitive_translate;
                    $infinitive->amount = $word->amount;
                    $infinitive->user_id = Yii::$app->user->id;
                    $infinitive->save();
                }

                $word->infinitive_id = $infinitive->id;
                $word->save();
            }
        }
    }

    public static function parseTextToWords($text)
    {
        $words = [];

        $symbols = array('!',',','.','\'','"','-',':',';','?',"\r",'(',')');

        $text = str_replace($symbols, '', $text);     # Удаляем из текста ненужные символы

        $text = str_replace("\n", ' ', $text);    # Заменяем переносы строк на пробелы

        $text_array = explode(' ',$text);    # 'Разрезаем' текст на слова

        foreach($text_array as $val){     # Переберем слова и исключим дубликаты
            if($val==''){continue;}
            $val = strtolower($val);
            if(array_key_exists($val, $words)){     # Если такое слово уже есть в массиве, увеличим счетчик
                $words[$val]++;
            } else {
                $words[$val] = 1;
            }
        }

        return $words;
    }

}
