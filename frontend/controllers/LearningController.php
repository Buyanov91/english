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
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;

class LearningController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->render('/site/index');
        }

        $words = Infinitive::findInfinitivesToStudy();

        $percent = Infinitive::calcPercentStudiedWords();

        return $this->render('index', ['words' => $words, 'percent' => $percent]);
    }

    public function actionAdd()
    {
        $words = Word::findNewWords();

        return $this->render('add', ['words' => $words]);
    }

    public function actionStudy($word_id)
    {
        $study = new Study();
        $study->addToStudy($word_id);
    }

    public function actionRandomWord()
    {
        $words = Infinitive::findInfinitivesToStudy();
        if(empty($words)) {
            return json_encode($words, JSON_UNESCAPED_UNICODE);
        } else {
            $random = array_rand($words);
            $translate = Translate::translate($words[$random]['infinitive']);
            $words[$random]['translate'] = $translate[$words[$random]['infinitive']]['def'][0]['tr'][0]['text'];
            return json_encode($words[$random], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actionKnow($infinitive_id, $status)
    {
        if($status == 0){
            return self::actionRandomWord();
        } else {
            Study::removeFromStudy($infinitive_id);
            return self::actionRandomWord();
        }
    }
}