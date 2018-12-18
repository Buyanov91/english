<?php

namespace frontend\controllers;

use app\models\Profile;
use app\models\Settings;
use app\models\UploadImage;
use Yii;
use yii\web\UploadedFile;

class SettingController extends MainController
{
        /**
     * @return string
     */
    public function actionIndex()
    {
        $profile = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
        $settings = Settings::find()->where(['user_id' => Yii::$app->user->id])->one();

        $avatar = new UploadImage();
        $old_avatar = '';

        if (isset($profile->avatar)) {
            $old_avatar = $profile->avatar;
        }

        if(empty($profile)){
            $profile = new Profile();
        }
        if(empty($settings)){
            $settings = new Settings();
        }

        if(Yii::$app->request->isPost){
            $avatar->file = UploadedFile::getInstance($profile, 'avatar');

            if($profile->load(Yii::$app->request->post())) {
                $profile->updateAttributesFromForm($old_avatar, $avatar);
            }

            if($settings->load(Yii::$app->request->post())){
                $settings->updateAttributesFromForm();

            }

            $this->refresh();
        }

        return $this->render('index', ['settings' => $settings, 'profile' => $profile]);
    }

}
