<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:28
 */

namespace frontend\controllers;

use app\models\Infinitive;
use app\models\Word;
use yii\web\Controller;

class PopularController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->render('/site/index');
        }

        $words = Word::find()
            ->innerJoinWith('infinitive')
            ->innerJoinWith('sentence')
            ->where('infinitive.user_id = '.\Yii::$app->user->id)
            ->groupBy('infinitive.id')
            ->orderBy('infinitive.amount DESC')
            ->limit(20)
            ->all();
//        debug($words);
        return $this->render('index', ['words' => $words]);
    }
}