<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:28
 */

namespace frontend\controllers;

use app\models\Word;

class PopularController extends MainController
{
    public function actionIndex()
    {
        $words = Word::findPopular();

        return $this->render('index', ['words' => $words]);
    }
}