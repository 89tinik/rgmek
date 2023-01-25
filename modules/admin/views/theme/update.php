<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Theme */

$this->title = 'Редактирование темы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="theme-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
