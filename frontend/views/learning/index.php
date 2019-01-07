<?php

/* @var $this yii\web\View
 * @var $words
 * @var $percent
 */

use yii\helpers\Html;

$this->title = 'English | Изучение';
?>
<div class="jumbotron">

    <?php if(empty($words)) : ?>
        <h1>У Вас нет слов к изучению</h1>
        <br>
        <p class="text-muted">Выберите слова из Ваших текстов.</p>
        <?= Html::a('Добавить слова', ['learning/add'], ['class' => 'btn btn-lg btn-info']) ?>
    <?php else: ?>
    <div class="main-study row">
        <div class="col-md-4 shadow">
            <br>
            <h4>На данный момент изученно:</h4>
            <p><?= $percent ?>% слов</p>
        </div>
        <div class="col-md-7 text-left">
            <?= Html::a('Начать изучение', ['learning/random-word'], ['class' => 'btn btn-lg btn-warning', 'id' => 'learn']) ?>
            <br><br>
            <?= Html::a('Добавить слова', ['learning/add'], ['class' => 'btn btn-lg btn-info', 'style' => 'width:215px']) ?>
        </div>
    </div>
    <div class="hide-study text-center">
        <h1 id="learn-word"></h1>
        <div id="translates" class="centered"></div>
        <br>
    </div>
    <div class="hide-end-button">
        <?= Html::a('Закончить обучение', '/learning', ['class' => 'btn btn-warning']) ?>
    </div>
    <?php endif; ?>

</div>
