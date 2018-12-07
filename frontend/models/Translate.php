<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 10:15
 */

namespace app\models;

/**
 * Class Translate
 * @package app\models
 */
class Translate
{
    const RUS_TO_ENG = 'ru-en';

    const ENG_TO_RUS = 'en-ru';

    const ENG_TO_ENG = 'en-en';

    const RUS_TO_RUS = 'ru-ru';

    const KEY_TO_API = 'dict.1.1.20181102T061057Z.1cde8c000cfdc4f7.bd4e27863b0270a2fe2e1ea6d0f712faeefdfaa4';

    /** @var string translated word*/
    public $translate;

    /** @var string infinitive from translated word*/
    public $infinitive;

    /** @var string word to translate*/
    public $word;

    /**
     * Translate constructor.
     * @param $word
     */
    public function __construct(string $word)
    {
        $this->word = $word;
    }

    /**
     * @param string $lang
     */
    public function translate(string $lang = self::ENG_TO_RUS): void
    {
        $url = $this->getUrl($lang, $this->word);
        self::prepareDataToTranslate($url);
    }

    /**
     * @param string $url
     */
    private function prepareDataToTranslate(string $url): void
    {
        $data = json_decode(file_get_contents($url,3), true);

        $infinitive = $data['def'][0]['text'];
        $translate = $data['def'][0]['tr'][0]['text'];

        $this->translate = $translate;
        $this->infinitive = $infinitive;
    }

    /**
     * @param string $lang
     * @param string $word
     * @return string
     */
    private function getUrl(string $lang, string $word): string
    {
        return 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.self::KEY_TO_API.'&lang='.$lang.'&text='.$word.'&flags=4';
    }

}