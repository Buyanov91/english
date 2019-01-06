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

class LearningController extends MainController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $words = Infinitive::findInfinitivesToStudy();

        $percent = Infinitive::calcPercentStudiedWords();

        return $this->render('index', ['words' => $words, 'percent' => $percent]);
    }

    /**
     * @return string
     */
    public function actionAdd()
    {
        $words = Word::findToAdd();
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
     * @param int $infinitive_id
     * @param int $answer_id
     * @return false|string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAnswer(int $infinitive_id, int $answer_id)
    {
        if ($infinitive_id === $answer_id) {
            Study::removeFromStudy($infinitive_id);
            return self::actionRandomWord();
        }

        return self::actionRandomWord();
    }
}