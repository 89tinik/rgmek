<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

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
    <?= $form->field($model, 'user_name')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userModel->full_name])->label('Пользователь'); ?>
    <?= $form->field($model, 'user_phone')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userModel->phone])->label('Телефон пользователя'); ?>
    <?= $form->field($model, 'user_email')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userModel->email])->label('E-mail пользователя'); ?>

    <?= $form->field($model, 'contract_id')->dropDownList(
        $userModel->getContractsList(),
        [
            'prompt' => 'Выберите договор',
            'class' => 'form-control styler select__default'
        ]
    ) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true]); ?>
    <?= $form->field($model, 'contact_name')->textInput(['value' => $userModel->full_name]); ?>
    <?= $form->field($model, 'phone')->textInput(['value' => $userModel->phone])->widget(MaskedInput::class, [
        'mask' => '89999999999',
    ]); ?>
    <?= $form->field($model, 'email')->textInput(['value' => $userModel->email]); ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Выйти из обращения без сохранения', ['messages/index'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
