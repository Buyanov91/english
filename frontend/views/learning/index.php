<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'English | Изучение';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron">

    <?php if(empty($words)) : ?>
        <h1>У Вас нет загруженных слов</h1>
        <br>
        <p class="text-muted">Выберите слова из Ваших текстов.</p>
        <?= Html::a('Добавить слова', ['learning/add'], ['class' => 'btn btn-lg btn-info']) ?>
    <?php else: ?>
        <?= Html::button('Начать изучение', ['class' => 'btn btn-lg btn-warning']) ?>
        <br><br>
        <?= Html::a('Добавить слова', ['learning/add'], ['class' => 'btn btn-lg btn-info']) ?>
    <?php endif; ?>

</div>
