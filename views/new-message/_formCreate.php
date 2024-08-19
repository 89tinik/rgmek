<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
/* @var $userModel \app\models\User */
/* @var $subject int */
?>

<div class="messages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject_id')->hiddenInput(['value' => $subject])->label(false); ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => $userModel->id])->label(false); ?>

    <?= $form->field($model, 'contract_id')->dropDownList(
        $userModel->getContractsList(),
        [
            'prompt' => 'Выберите договор',
            'class' => 'form-control styler select__default'
        ]
    ) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true]);?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
