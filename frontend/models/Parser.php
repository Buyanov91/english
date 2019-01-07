<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 07.01.19
 * Time: 14:14
 */

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Parser extends ActiveRecord
{
    private $lang;
    private $text_id;
    private $text_text;
    private $text_record;

    public function __construct(
        Text $text,
        array $config = []
    )
    {
        $this->text_id = $text->id;
        $this->text_text = $text->text;
        $this->text_record = $text;
        $this->lang = Setting::getLang();
        parent::__construct($config);
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function parseText(): bool
    {
        $sentences = explode('. ', trim($this->text_text));

        foreach ($sentences as $sent) {
            $sentence = new Sentence();
            $sentence->updateAttributesFromText($this->text_id, $sent);

            $words = self::parseTextToWords($sent);

            foreach ($words as $newWord => $amount) {

                $translate = new Translate($newWord);
                $translate->translate($this->lang);

                if (empty($translate->infinitive)) {
                    $this->text_record->delete();
                    $session = Yii::$app->session;
                    $session->setFlash('error', 'Ошибка в тексте или в настройках выбран не тот язык.');
                    return false;
                } else {
                    $infinitive = new Infinitive();
                    $infinitive->updateAttributesFromWord($translate->infinitive, $translate->translate, $amount);

                    $word = new Word();
                    $word->updateAttributesFromSentences($newWord, $sentence->id, $infinitive->id);
                }
            }
        }
        return true;
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