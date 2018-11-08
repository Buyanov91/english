<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 01.11.18
 * Time: 14:18
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::$app->params['pathUploads'];
            $this->file->saveAs($path . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }

    public static function uploadWords($text_id, $text)
    {
        $sentences = explode('.', $text);

        foreach ($sentences as $sent) {
            $sentence = new Sentence();
            $sentence->sentence = $sent;
            $sentence->text_id = $text_id;
            $sentence->save();

            $words = Text::parseText($sent);

            foreach ($words as $newWord => $amount) {

                $infinitive_trans = Translate::translate($newWord, Translate::ENG_TO_ENG);
                $infinitive_translate = $infinitive_trans[$newWord]['def'][0]['text'];

                $word = new Word();
                $word->sentence_id = $sentence->id;
                $word->word = $newWord;
                $word->amount = $amount;

                $id = Infinitive::checkInfinitive($infinitive_translate);
                debug($id);
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
}