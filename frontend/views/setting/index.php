<?php
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
?>

<div class="container">
    <h2>Пользователь <b><?= Yii::$app->user->identity->username ?></b></h2>
</div>

<?php $form = ActiveForm::begin(['id' => 'profile']); ?>

<div class="col-md-4">

    <?= $form->field($profile, 'avatar')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'], 'language' => 'ru'
        ]); ?>
    <br>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-lg', 'name' => 'login-button']) ?>

</div>

<div class="col-md-offset-1 col-md-6">

    <?= $form->field($profile, 'firstname')->textInput(['autofocus' => true]) ?>

    <?= $form->field($profile, 'lastname')->textInput() ?>

    <?= $form->field($profile, 'age')->textInput(['type' => 'number']) ?>

    <?= $form->field($settings, 'lang')->dropDownList([
            '0' => 'С Английского на Русский',
            '1' => 'С Русского на Английский'
    ]) ?>

    <?= $form->field($settings, 'attempts_to_study')->dropDownList([
        '1' => '1',
        '2' => '2',
        '3' => '3'
    ]) ?>

</div>

<?php ActiveForm::end(); ?>

