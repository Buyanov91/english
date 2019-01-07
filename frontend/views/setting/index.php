<?php
/**
 * @var $this yii\web\View
 * @var $profile
 * @var $settings
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="container">
    <h2>Пользователь <b><?= Yii::$app->user->identity->username ?></b></h2>
</div>
<br>
<?php $form = ActiveForm::begin(['id' => 'profile']); ?>

<div class="col-md-4">
    <?php if (empty($profile->avatar)) : ?>

        <div class="image">
            <?= Html::img('images/avatar.png', ['alt' => 'Фото'])?>
        </div>

    <?php else: ?>

        <div class="image">
            <?= Html::img($profile->avatar, ['alt' => 'Фото'])?>
        </div>

    <?php endif; ?>

    <?= $form->field($profile, 'avatar')->fileInput()->label(false); ?>

</div>

<div class="col-md-offset-1 col-md-6">

    <?= $form->field($profile, 'firstname')->textInput() ?>

    <?= $form->field($profile, 'lastname')->textInput() ?>

    <?= $form->field($profile, 'age')->textInput(['type' => 'number']) ?>

    <?= $form->field($settings, 'lang')->dropDownList([
            'en-ru' => 'С Английского на Русский',
            'ru-en' => 'С Русского на Английский'
    ]) ?>

    <?= $form->field($settings, 'attempts')->dropDownList([
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5'
    ]) ?>

</div>

<div class="clearfix"></div>

<div class="container">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-lg', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); ?>

