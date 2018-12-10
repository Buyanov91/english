<?php

namespace frontend\controllers;

use app\models\Profile;
use app\models\Settings;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $settings = new Settings();
        $profile = new Profile();
        return $this->render('index', ['settings' => $settings, 'profile' => $profile]);
    }

}
