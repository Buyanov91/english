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
use app\models\Translate;
use app\models\Word;
use yii\web\Controller;
use Yii;

class LearningController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->render('/site/index');
        }

        $words = Infinitive::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 1])
            ->all();

        $count_study = Infinitive::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 2])
            ->count();

        $count_all = Infinitive::find()->count();

        $persent = round($count_study/$count_all*100, 1);

        return $this->render('index', ['words' => $words, 'percent' => $persent]);
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
        $infinitive_id = Word::find()
            ->innerJoinWith('infinitive')
            ->where('word.id = '.$word_id)
            ->asArray()
            ->one();
        $study = new Study();
        $study->user_id = Yii::$app->user->id;
        $study->infinitive_id = $infinitive_id['infinitive_id'];
        $study->status = Study::STATUS_STUDY;
        $study->save();
    }

    public function actionRandomWord()
    {
        $words = Infinitive::find()
            ->innerJoinWith('study')
            ->where(['study.user_id' => Yii::$app->user->id])
            ->andWhere(['study.status' => 1])
            ->asArray()
            ->all();
        if(empty($words)) {
            return $this->render('learning/index');
        }
        $random = array_rand($words);
        $translate = Translate::translate($words[$random]['infinitive']);
        $words[$random]['translate'] = $translate[$words[$random]['infinitive']]['def'][0]['tr'][0]['text'];
        return json_encode($words[$random], JSON_UNESCAPED_UNICODE);
    }

    public function actionKnow($infinitive_id, $status)
    {
        if($status === 0){
            return self::actionRandomWord();
        } else {
            $study = Study::find()
                ->where('infinitive_id='.$infinitive_id)
                ->andWhere('user_id='.\Yii::$app->user->id)
                ->one();
            $study->updateCounters(['status' => 1]);
            return self::actionRandomWord();
        }
    }
}