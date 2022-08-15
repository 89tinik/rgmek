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
            'options' => [
                'class' => 'c-form'
            ],
            'fieldConfig' => [
                'template' => "<div class=\"field \">
							{input}{error}
						</div>",
//                'options' => [
//                    'tag' => false
//                ]
            ]
        ]); ?>
        <div class="title">Войти в личный кабинет небытового потребителя</div>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            echo Yii::$app->session->getFlash('error');
        }
        if (Yii::$app->session->hasFlash('login')) {
            $loginForm->username = Yii::$app->session->getFlash('login');
        }
        ?>

        <?= $form->field($loginForm, 'username')->textInput(['placeholder' => 'Логин', 'autofocus' => true]) ?>
        <?= $form->field($loginForm, 'password', ['template' => '<div class="field ">
							{input}
							<div class="eye"></div>	
                            
							{error}
						</div>'])->passwordInput(['placeholder' => 'Пароль']) ?>
        <?= Html::submitButton('Войти') ?>

        <div class="wrong-link">
            <?= Html::a('Зарегистрироваться', ['login/registration'],['class'=>'ploader']) ?>
            <span>&nbsp; &nbsp; </span>
            <?= Html::a('Восстановить пароль', ['login/repassword'],['class'=>'ploader']) ?>
        </div>
        <?php 
            ActiveForm::end(); 
            //print_r(getallheaders());
        ?>
    </div>
</div>