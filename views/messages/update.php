<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */

$this->title = Html::encode($model->subject->title) . ' <span>' . Html::encode($model->admin_num) . '</span>';
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="messages-update">

    <h1>Обращение <?= (!empty($model->admin_num)) ? '№'.Html::encode($model->admin_num) . ' от ' .  Yii::$app->formatter->asDate($model->published, 'php:d.m.Y') : '';?></h1>
    <h2>«<?=Html::encode($model->subject->title)?>»</h2>
    <h3>История обработки</h3>
    <ul>
        <?php foreach ($model->messageHistory as $history): ?>
            <li><b><?=Yii::$app->formatter->asDate($history->created, 'php:d.m.Y H:i')?></b> - <?= $history->log ?></li>
        <?php endforeach; ?>
    </ul>
    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
