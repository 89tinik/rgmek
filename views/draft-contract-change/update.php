<?php

use app\models\DraftContractChange;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DraftContractChangeForm */
/* @var $userModel yii\web\User */
/* @var $contractsInfo array */

$this->title = 'Формирование проекта соглашения об изменении цены действующего контракта (договора)';
$this->params['breadcrumbs'][] = ['label' => 'Draft Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Заключение/изменение договора</strong><span class="sep"></span>
        <span><?= Html::encode($this->title) ?></span>
    </div>
</div>
<div class="draft-contract-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'userModel' => $userModel,
        'contractsInfo' => $contractsInfo,
    ]) ?>

</div>
