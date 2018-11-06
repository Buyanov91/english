<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:27
 */

namespace frontend\controllers;

use app\models\Infinitive;
//use app\models\Study;
use yii\web\Controller;
use Yii;

class LearningController extends Controller
{
    public function actionIndex()
    {
        $words = Infinitive::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->all();

        return $this->render('index', ['words' => $words]);
    }
}