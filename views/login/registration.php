<?php

/* @var $this yii\web\View */

/* @var $registerForm  */
/* @var $kpp */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;

$this->title = 'Регистрация';
?>

<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'registerForm'
            ],
            'fieldConfig' => [
                'template' => "
						<div class=\"field \">
							{input}{error}
						</div>"
            ]
        ]); ?>

        <div class="title"><?=$this->title;?></div>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            echo Yii::$app->session->getFlash('error');
        }
        ?>
        <?= $form->field($registerForm, 'inn')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,12}',
        ])->textInput(['placeholder' => 'ИНН']) ?>
        <?php if($kpp):?>
        <?= $form->field($registerForm, 'kpp')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '999999999',
            ])->textInput(['placeholder' => 'КПП', 'required'=>'required']) ?>
        <?php endif;?>
        <?= $form->field($registerForm, 'contract')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,}',
        ])->textInput(['placeholder' => '№ договора']) ?>
        <?= $form->field($registerForm, 'password')->passwordInput(['placeholder' => 'Пароль']) ?>
        <?= $form->field($registerForm, 'rePassword')->passwordInput(['placeholder' => 'Повторите пароль']) ?>

        <?= $form->field($registerForm, 'method')->radioList(['0'=>'E-mail', '1'=>'Телефон'],  ['itemOptions' => ['class' => 'styler']]); ?>
        <?= $form->field($registerForm, 'email')->textInput(['placeholder' => 'E-mail', 'class'=>'email form-control']) ?>
        <?= $form->field($registerForm, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '89999999999',
        ])->textInput(['placeholder' => 'Телефон', 'class'=>'phone form-control']) ?>
        <?= Html::submitButton('Регистрация') ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

