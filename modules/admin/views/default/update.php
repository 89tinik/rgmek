<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model  */
/* @var $modelForm  */

$this->title = 'Обновление данных пользователя';
//$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $modelForm,
    ]) ?>

</div>
