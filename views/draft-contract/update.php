<?php

use app\models\DraftContract;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DraftContractForm */
/* @var $userModel yii\web\User */
/* @var $contractsInfo array */

$this->title = DraftContract::TITLE;
$this->params['breadcrumbs'][] = ['label' => 'Draft Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="draft-contract-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'userModel' => $userModel,
        'contractsInfo' => $contractsInfo,
    ]) ?>

</div>
