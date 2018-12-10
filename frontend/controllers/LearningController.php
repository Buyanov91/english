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

class LearningController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->render('/site/index');
        }

        $words = Infinitive::findInfinitivesToStudy();

        $percent = Infinitive::calcPercentStudiedWords();

        return $this->render('index', ['words' => $words, 'percent' => $percent]);
    }

    /**
     * @return string
     */
    public function actionAdd()
    {
        $words = Word::findNewWords();

        return $this->render('add', ['words' => $words]);
    }

    /**
     * @param $word_id
     */
    public function actionStudy(int $word_id): void
    {
        $study = new Study();
        $study->addToStudy($word_id);
    }

    /**
     * @return false|string
     */
    public function actionRandomWord()
    {
        $words = Infinitive::getRandomWordForJson();

        return json_encode($words, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $infinitive_id
     * @param $status
     * @return false|string
     */
    public function actionKnow(int $infinitive_id, int $status)
    {
        if($status == 0){
            return self::actionRandomWord();
        } else {
            Study::removeFromStudy($infinitive_id);
            return self::actionRandomWord();
        }
    }
}