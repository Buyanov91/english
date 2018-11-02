<?php
namespace frontend\controllers;

use app\models\Infinitive;
use app\models\Sentence;
use app\models\Text;
use app\models\Word;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use app\models\UploadForm;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $text = new Text();

        $file = new UploadForm();

        if (Yii::$app->request->isPost) {

            $file->file = UploadedFile::getInstance($file, 'file');

            if ($file->upload()) {
                $path = Yii::$app->params['pathUploads'].$file->file->name;
                $textFile = file_get_contents($path);

                $text->text = $textFile;
                $text->filepath = $path;
                $text->text_md5 = $text->textMD5($textFile);
            }

            if($text->load(Yii::$app->request->post())) {
                $text->text_md5 = $text->textMD5(Yii::$app->request->post('text'));
            }

            $text->user_id = Yii::$app->user->id;

            if($text->save()) {
                return $this->getAllWords($text->id, $text->text);
            }
        }
        return $this->render('index', ['text' => $text, 'file' => $file]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionPopular()
    {
        return $this->render('popular');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionLearning()
    {
        return $this->render('learning');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    protected function getAllWords($text_id, $text)
    {
        $sentences = explode('.', $text);

        foreach ($sentences as $sent) {
            $sentence = new Sentence();
            $sentence->sentence = $sent;
            $sentence->text_id = $text_id;
            $sentence->save();

            $words = $this->getWordsFromText($sent);

            foreach ($words as $newWord => $amount) {
                $word = new Word();
                $word->sentence_id = $sentence->id;
                $word->word = $newWord;
                $word->amount = $amount;
                $word->save();

                $infinitives = $this->translate($newWord, 'infinitive');
                foreach ($infinitives as $i => $key){
                    foreach ($key['def'] as $key) {
                        $infinitive = new Infinitive();
                        $infinitive->infinitive = $key['text'];
                        $infinitive->word_id = $word->id;
                        $infinitive->amount = $word->amount;
                        $infinitive->save();
                    }
                }
            }
        }
        return $this->goHome();
    }

    public function getWordsFromText($text)
    {
        $words = [];

        $symbols = array('!',',','.','\'','"','-',':',';','?',"\r",'(',')');

        $text = str_replace($symbols, '', $text);     # Удаляем из текста ненужные символы

        $text = str_replace("\n", ' ', $text);    # Заменяем переносы строк на пробелы

        $text_array = explode(' ',$text);    # 'Разрезаем' текст на слова

        foreach($text_array as $val){     # Переберем слова и исключим дубликаты
            if($val==''){continue;}
            $val = strtolower($val);
            if(array_key_exists($val, $words)){     # Если такое слово уже есть в массиве, увеличим счетчик
                $words[$val]++;
            } else {
                $words[$val] = 1;
            }
        }

        return $words;
    }

    public function translate($words, $type = 'translate')
    {
        $key ='dict.1.1.20181102T061057Z.1cde8c000cfdc4f7.bd4e27863b0270a2fe2e1ea6d0f712faeefdfaa4';
        $lang = 'en-ru';

        if($type === 'infinitive') {
            $lang = 'en-en';
        }
        $arr = [];
        if(is_array($words)){
            foreach($words as $word => $val){
                $url_to_dict = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.$key.'&lang='.$lang.'&text='.$word.'&flags=4';
                $arr[$word][$val] = json_decode(file_get_contents($url_to_dict,3), true);
            }
        } else {
            $url_to_dict = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.$key.'&lang='.$lang.'&text='.$words.'&flags=4';
            $arr[$words] = json_decode(file_get_contents($url_to_dict,3), true);
        }


        return $arr;
    }

}
