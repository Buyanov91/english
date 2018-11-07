<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:27
 */

namespace frontend\controllers;

use app\models\Infinitive;
use app\models\Study;
use app\models\Word;
use yii\web\Controller;
use Yii;

class LearningController extends Controller
{
    public function actionIndex()
    {
        $words = Infinitive::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 1])
            ->all();

        return $this->render('index', ['words' => $words]);
    }

    public function actionAdd()
    {
        $words = Word::find()
            ->select('word.*, sentence.sentence')
            ->innerJoinWith('text')
            ->where(['text.user_id' => Yii::$app->user->id])
            ->orderBy('word.word')
            ->asArray()
            ->all();
        return $this->render('add', ['words' => $words]);
    }

    public function actionStudy($word_id)
    {
        $infinitive_id = Infinitive::find()
            ->select('id')
            ->where('word_id = '.$word_id)
            ->asArray()
            ->one();
        $study = new Study();
        $study->user_id = Yii::$app->user->id;
        $study->infinitive_id = $infinitive_id['id'];
        $study->status = Study::STATUS_STUDY;
        $study->save();
    }
}