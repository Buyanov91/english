<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 06.11.18
 * Time: 11:28
 */

namespace frontend\controllers;

use yii\web\Controller;

class PopularController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}