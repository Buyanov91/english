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
        $this->prepareDataToTranslate($url);
    }

    /**
     * @param string $url
     */
    private function prepareDataToTranslate(string $url): void
    {
        $data = $this->checkWordToTranslate($url);

        if ($data) {
            $infinitive = $data[0]['text'];
            $translate = $data[0]['tr'][0]['text'];

            $this->translate = $translate;
            $this->infinitive = $infinitive;
        }
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

    /**
     * @param $url
     * @return array|null
     */
    private function checkWordToTranslate($url): ?array
    {
        try {
            file_get_contents($url);
        } catch (\Exception $e) {
            return null;
        }

        $content = file_get_contents($url);
        $data = json_decode($content, true);

        if (empty($data['def'])) {
            return null;
        }
        return $data['def'];
    }

}