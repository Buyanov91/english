<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:28
 */

namespace frontend\controllers;

use app\models\Word;
use app\models\Text;

class PopularController extends MainController
{
    public function actionIndex()
    {
        $popularWords = Word::findPopular();

        $allWords = Text::findAllWords();

        $studiedWords = Word::findStudiedWords();

        return $this->render('index', [
            'popularWords' => $popularWords,
            'allWords' => $allWords,
            'studiedWords' => $studiedWords
        ]);
    }
}