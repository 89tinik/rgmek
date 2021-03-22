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
        <?php $form = ActiveForm::begin(); ?>

        <div class="title">Регистрация</div>
        <?php
        if (Yii::$app->session->hasFlash('success')){
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')){
            echo Yii::$app->session->getFlash('error');
        }
        ?>
        <?= $form->field($registerForm, 'inn')?>
        <?= $form->field($registerForm, 'contract')?>
        <?= $form->field($registerForm, 'password')?>
        <?= $form->field($registerForm, 'rePassword')?>
        <?= $form->field($registerForm, 'email')?>
        <?= $form->field($registerForm, 'phone')?>
        <?= Html::submitButton('Регистрация')?>
        <?php ActiveForm::end();?>
    </div>
</div>

