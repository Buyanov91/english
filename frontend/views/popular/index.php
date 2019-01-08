<?php

/* @var $this yii\web\View */

use app\models\Study;
use yii\bootstrap\Modal;

$this->title = 'English | Словарь';
?>
<div class="jumbotron">
    <div class="row dictionary">
        <h1 class="h2-pop">Словарь</h1>
        <div class="col-md-4">
            <h3>Популярные слова</h3>
            <br>
            <div class="text-center">
                <?php if(empty($popularWords)) : ?>
                    <p>У Вас нет загруженных слов</p>
                <?php else: ?>
                    <?php foreach ($popularWords as $word) : ?>
                        <div class="shadow row text-left">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Встречается <?= $word->infinitive['amount'] ?> раз</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Все слова</h3>
            <br>
            <div class="text-center">
                <?php if(empty($allWords)) : ?>
                    <p>У Вас нет загруженных слов</p>
                <?php else: ?>
                    <?php foreach ($allWords as $word) : ?>
                        <div class="shadow <?= ((int)$word['status'] === Study::STATUS_STUDIED)?'status-study':'status-unstudied'?>">
                            <p>
                                <?php Modal::begin([
                                    'header' => '<h2>'.ucfirst($word['infinitive']).'</h2>',
                                    'toggleButton' => [
                                        'label' => ucfirst($word['infinitive']),
                                        'tag' => 'a',
                                        'class' => 'text-muted',
                                    ],
                                ]); ?>
                            <h4>Перевод:</h4>
                            <p><?= $word['translate'] ?></p>
                            <h4>Пример предложения:</h4>
                            <p><?= $word['sentence'] ?></p>
                            <?php Modal::end(); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Выученные слова</h3>
            <br>
            <div class="text-center">
                <?php if(empty($studiedWords)) : ?>
                    <p>У Вас нет выученных слов</p>
                <?php else: ?>
                    <?php foreach ($studiedWords as $word) : ?>
                        <div class="shadow">
                            <p>
                                <?php Modal::begin([
                                    'header' => '<h2>'.ucfirst($word['infinitive']).'</h2>',
                                    'toggleButton' => [
                                        'label' => ucfirst($word['infinitive']). " - ". ucfirst($word['translate']),
                                        'tag' => 'a',
                                        'class' => 'text-muted',
                                    ],
                                ]); ?>
                            <h4>Перевод:</h4>
                            <p><?= $word['translate'] ?></p>
                            <h4>Пример предложения:</h4>
                            <p><?= $word['sentence'] ?></p>
                            <?php Modal::end(); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
