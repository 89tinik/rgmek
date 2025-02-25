<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
/* @var $userModel \app\models\User */
/* @var $subject int */
/* @var $profileInfo array */
?>

<?php
if (!empty($userModel->phone)) {
    $userPhone = $userModel->phone;
} else {
    if (is_array($profileInfo['PhoneCity']) && !isset($profileInfo['PhoneCity']['Value'])) {
        $userPhone = '';
        foreach ($profileInfo['PhoneCity'] as $arr) {
            if (empty($phoneCity)) {
                $userPhone = $arr['Value'];
            } else {
                $userPhone .= ', ' . $arr['Value'];
            }
        }
    } else {
        $userPhone = $profileInfo['PhoneCity']['Value'];
    }
}

$userEmail = (!empty($userModel->email)) ? $userModel->email : $profileInfo['Email'][0]['Value'];

$contractsList = $userModel->getContractsList();
$dropDownOptions = [
    'class' => 'form-control styler select__default'
];

if (count($contractsList) > 1) {
    // Добавляем prompt, если элементов больше одного
    $dropDownOptions['prompt'] = 'Выберите номер договора';
}
?>

<div class="messages-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'filesUploadNames')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'subject_id')->hiddenInput(['value' => $subject])->label(false); ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => $userModel->id])->label(false); ?>
    <?= $form->field($model, 'user_name')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userModel->full_name])->label('Потребитель'); ?>
    <?= $form->field($model, 'user_phone')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userPhone])->label('Телефон потребителя'); ?>
    <?= $form->field($model, 'user_email')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userEmail])->label('E-mail потребителя'); ?>

    <?= $form->field($model, 'contract_id')->dropDownList($contractsList, $dropDownOptions) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true]); ?>
    <ul id="filesList"></ul>
    <?= $form->field($model, 'contact_name')->textInput(['value' => $userModel->full_name]); ?>
    <?= $form->field($model, 'phone')->textInput(['value' => $userPhone]); ?>
    <?= $form->field($model, 'email')->textInput(['value' => $userEmail]); ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Выйти из обращения без сохранения', ['messages/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::button('Посмотреть обращение', ['class' => 'btn btn-success ajax-pdf']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
