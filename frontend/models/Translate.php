<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 10:15
 */

namespace app\models;


class Translate
{
    public static function translate($words, $type = 'translate')
    {
        $key ='dict.1.1.20181102T061057Z.1cde8c000cfdc4f7.bd4e27863b0270a2fe2e1ea6d0f712faeefdfaa4';
        $lang = 'en-ru';

        if($type === 'infinitive') {
            $lang = 'en-en';
        }
        $arr = [];
        if(is_array($words)){
            foreach($words as $word => $val){
                $url_to_dict = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.$key.'&lang='.$lang.'&text='.$word.'&flags=4';
                $arr[$word][$val] = json_decode(file_get_contents($url_to_dict,3), true);
            }
        } else {
            $url_to_dict = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.$key.'&lang='.$lang.'&text='.$words.'&flags=4';
            $arr[$words] = json_decode(file_get_contents($url_to_dict,3), true);
        }

        return $arr;
    }
}