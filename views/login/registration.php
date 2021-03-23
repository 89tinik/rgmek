<?php

/* @var $this yii\web\View */
/* @var $registerForm  */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;

$this->title = 'Регистрация';
?>

<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "
						<div class=\"field \">
							{input}{error}
						</div>",
//                'options' => [
//                    'tag' => false
//                ]
            ]
        ]); ?>

        <div class="title">Регистрация</div>
        <?php
        if (Yii::$app->session->hasFlash('success')){
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')){
            echo Yii::$app->session->getFlash('error');
        }
        ?>
        <?= $form->field($registerForm, 'inn')->textInput(['placeholder' => 'ИНН'])?>
        <?= $form->field($registerForm, 'contract')->textInput(['placeholder' => '№ договора'])?>
        <?= $form->field($registerForm, 'password')->passwordInput(['placeholder' => 'Пароль'])?>
        <?= $form->field($registerForm, 'rePassword')->passwordInput(['placeholder' => 'Повторите пароль'])?>
        <?= $form->field($registerForm, 'email')->textInput(['placeholder' => 'E-mail'])?>
        <?= $form->field($registerForm, 'phone')->textInput(['placeholder' => 'Телефон'])?>
        <?= Html::submitButton('Регистрация')?>
        <?php ActiveForm::end();?>
    </div>
</div>

