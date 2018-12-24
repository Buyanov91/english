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
        <div id="translates" class="row"></div>
        <br>
    </div>
    <div class="hide-translate-true">
        <div class="row">
            <div class="col-md-6 text-right">
                <?= Html::a('Верно', '', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <div class="hide-translate-false">
        <div class="row">
            <div class="col-md-6 text-right">
                <?= Html::a('Не верно', '', ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="hide-end-button">
        <?= Html::a('Закончить обучение', '/learning', ['class' => 'btn btn-warning']) ?>
    </div>
    <?php endif; ?>

</div>
