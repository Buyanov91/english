<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'English';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Приятного изучения!</h1>
        <br>
        <?php if(Yii::$app->user->isGuest) : ?>

            <h3>Пожалуйста зарегистрируйтесь.</h3>

        <?php else: ?>

            <div id="download">
                <p class="lead">Загрузите пожалуйста, свой текст.</p>
                <p><a id="main-btn" class="btn btn-lg btn-success" href="javascript:">Загрузить</a></p>
            </div>

            <div id="show-btn" class="row">
                <div class="col-md-6">
                    <p><a id="text" class="btn btn-info" href="javascript:">Написать текст</a></p>
                </div>
                <div class="col-md-6">
                    <p><a id="file" class="btn btn-info" href="javascript:">Загрузить файл</a></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>

                <div class="col-md-7">
                    <?php $form = ActiveForm::begin(['id' => 'upload-text-form']); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($text, 'text')->textarea(['autofocus' => true, 'rows' => '6',
                                'placeholder' => 'Напишите или скопируйте Ваш текст'])->label(false) ?>
                        </div>
                        <div class="form-group col-md-4">
                            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>

                    <?php $form = ActiveForm::begin(['id' => 'upload-file-form']); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($text, 'filepath')->fileInput()->label(false) ?>
                        </div>
                        <div class="form-group col-md-4">
                            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <div class="body-content text-center">

        <?php if(empty($words)) : ?>

        <p>У Вас пока нет загруженных слов..</p>

        <?php else: ?>

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

        <?php endif; ?>

    </div>
</div>
