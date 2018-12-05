<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:28
 */

namespace frontend\controllers;

use app\models\Word;
use yii\web\Controller;

class PopularController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->render('/site/index');
        }

        $words = Word::findPopularInfinitives();

        return $this->render('index', ['words' => $words]);
    }
}