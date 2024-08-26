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

    <h1><?= $this->title ?></h1>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
