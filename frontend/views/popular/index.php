<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = 'English | Популярные';
?>
<div class="jumbotron">
    <h3>Популярные слова</h3>
    <br>
    <div class="text-center row">
        <?php if(empty($words)) : ?>
            <h1>У Вас нет загруженных слов</h1>
        <?php else: ?>
        <?php foreach ($words as $word) : ?>
        <div class="shadow col-md-2">
            <p>
                <?php Modal::begin([
                    'header' => '<h2>'.ucfirst($word->infinitive['infinitive']).'</h2>',
                    'toggleButton' => [
                        'label' => ucfirst($word->infinitive['infinitive']),
                        'tag' => 'a',
                        'class' => 'text-muted',
                    ],
                ]); ?>
            <h4>Пример предложения:</h4>
            <p><?= $word->sentence['sentence'] ?></p>
            <?php Modal::end(); ?>
            <small class="text-muted">Встречается <?= $word->infinitive['amount'] ?> раз</small>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
