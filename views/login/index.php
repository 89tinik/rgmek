<?php

/* @var $this yii\web\View */

/* @var $loginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Авторизация';
?>
<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "<div class=\"field \">
							{input}{error}
						</div>",
//                'options' => [
//                    'tag' => false
//                ]
            ]
        ]); ?>
        <div class="title">Войти</div>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            echo Yii::$app->session->getFlash('error');
        }
        ?>

        <?= $form->field($loginForm, 'username')->textInput(['placeholder' => 'Логин', 'autofocus' => true]) ?>
        <?= $form->field($loginForm, 'password')->passwordInput(['placeholder' => 'Пароль']) ?>
        <?= Html::submitButton('Войти') ?>
        <div class="wrong-link">
            <?= Html::a('Зарегистрироваться', ['login/registration']) ?>
            <span>&nbsp; &nbsp; </span>
            <?= Html::a('Восстановить пароль', ['login/repassword']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
