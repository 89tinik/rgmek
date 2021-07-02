<?php

/* @var $this yii\web\View */

/* @var $registerForm  */
/* @var $kpp */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;

$this->title = 'Восстановление пароля';
?>

<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'registerForm',
                'class' => 'c-form'
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

        <?= $form->field($registerForm, 'contract')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,}',
        ])->textInput(['placeholder' => '№ договора']) ?>
        <?= $form->field($registerForm, 'password', ['template' => '<div class="field ">
							{input}
							<div class="eye left"></div>	
							<div class="label-info"></div>
                            <div class="label-error" style="display: none;">
                                Запомните или сохраните пароль. Он будет использоваться при входе в личны кабинет.
                                <div class="close"></div>
                            </div>
							{error}
						</div>'])->passwordInput(['placeholder' => 'Пароль']) ?>
        <?= $form->field($registerForm, 'rePassword', ['template' => '<div class="field ">
							{input}
							<div class="eye"></div>
							{error}
						</div>'])->passwordInput(['placeholder' => 'Повторите пароль']) ?>

        <?= $form->field($registerForm, 'method')->radioList(['0'=>'E-mail', '1'=>'Телефон'],  ['itemOptions' => ['class' => 'styler']]); ?>
        <?= $form->field($registerForm, 'email', ['template' => '<div class="field ">
							{input}
							<div class="label-info"></div>
                            <div class="label-error" style="display: none;">
                                На этот адрес придет код для подтверждения регистрации
                                <div class="close"></div>
                            </div>
							{error}
						</div>'])->textInput(['placeholder' => 'E-mail', 'class'=>'email form-control']) ?>
        <?= $form->field($registerForm, 'phone', ['template' => '<div class="field ">
							{input}
							<div class="label-info"></div>
                            <div class="label-error" style="display: none;">
                                На этот номер придет код для подтверждения регистрации
                                <div class="close"></div>
                            </div>
							{error}
						</div>'])->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '89999999999',
        ])->textInput(['placeholder' => 'Телефон', 'class'=>'phone form-control']) ?>
        <?= Html::submitButton('Изменить пароль') ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

