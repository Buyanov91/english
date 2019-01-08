<?php

/* @var $this yii\web\View
 * @var $words
 * @var $percent
 */

use yii\helpers\Html;

$this->title = 'English | Изучение';
?>
<div class="jumbotron">

    <div class="main-study row">
        <div class="col-md-4 shadow">
            <br>
            <h4>На данный момент изученно:</h4>
            <p><?= $percent ?>% слов</p>
        </div>
        <div class="col-md-7 text-left">
            <?php if(empty($words)) : ?>
                <?= Html::a('Слов к изучению нет', ['learning/random-word'], ['class' => 'btn btn-lg btn-warning disabled', 'id' => 'learn']) ?>
            <?php else: ?>
                <?= Html::a('Начать изучение', ['learning/random-word'], ['class' => 'btn btn-lg btn-warning', 'id' => 'learn']) ?>
            <?php endif; ?>
            <br><br>
            <?= Html::a('Добавить слова', ['learning/add'], ['class' => 'btn btn-lg btn-info']) ?>
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

</div>
