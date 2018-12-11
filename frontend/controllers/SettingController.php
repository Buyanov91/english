<?php

namespace frontend\controllers;

use app\models\Profile;
use app\models\Settings;
use Yii;
use yii\web\UploadedFile;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $profile = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
        $settings = Settings::find()->where(['user_id' => Yii::$app->user->id])->one();

        if(empty($profile)){
            $profile = new Profile();
        }
        if(empty($settings)){
            $settings = new Settings();
        }

        if(Yii::$app->request->isPost){
            $avatar = UploadedFile::getInstance($profile, 'avatar');

            if($profile->load(Yii::$app->request->post())){
                $profile->updateAttributesFromForm($avatar);
                $profile->save();
            }
            if($settings->load(Yii::$app->request->post())){
                $settings->updateAttributesFromForm();
                $settings->save();
            }

            $this->refresh();
        }

        return $this->render('index', ['settings' => $settings, 'profile' => $profile]);
    }

}
