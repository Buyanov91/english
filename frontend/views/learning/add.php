<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = 'English | Изучение';
$count = 0;
?>
<div class="jumbotron">

    <?php if(empty($words)) : ?>
        <h1>У Вас нет загруженных слов</h1>
        <br>
        <p class="text-muted">Загрузите свои тексты</p>
        <?= Html::a('Загрузить', ['/'], ['class' => 'btn btn-lg btn-success']) ?>
    <?php else: ?>
        <h2>Выберите слова</h2>
        <br>
        <div class="row">
            <?php foreach ($words as $word) : ?>
            <?php if(empty($word['study']['status'])) : ?>
                <?php $count++; ?>
                <div id="<?=$word['id']?>" class="col-md-3">
                    <p>
                        <?php Modal::begin([
                            'header' => '<h2>'.ucfirst($word['word']).'</h2>',
                            'toggleButton' => [
                                'label' => ucfirst($word['word']),
                                'tag' => 'a',
                                'class' => 'text-muted',
                            ],
                        ]); ?>
                        <h4>Пример предложения:</h4>
                        <p><?= $word['sentence'] ?></p>
                        <?= Html::a('Добавить', ['/learning/study?word_id='.$word['id']], ['class' => 'btn btn-warning word-link']) ?>
                        <?php Modal::end(); ?>
                    </p>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php if($count === 0): ?>
    <p class="text-muted">У Вас нет новых слов для изучения, либо перейдите к изучению, либо загрузите новые.</p>
    <?php endif; ?>
    <br>
    <?= Html::a('Вернуться к изучению', ['/learning'], ['class' => 'btn btn-lg btn-success']) ?>
    <?php endif; ?>

</div>

