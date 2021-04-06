<?php

/* @var $this yii\web\View */

/* @var $verificationForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подтверждение контактных данных';
?>
<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "<div class=\"field \">
							{input}{error}
						</div>",
            ]
        ]); ?>
        <div class="title">Проверочный код</div>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            echo Yii::$app->session->getFlash('error');
        }
        ?>

        <?= $form->field($verificationForm, 'code')->textInput(['placeholder' => 'Код', 'autofocus' => true]) ?>
        <?= Html::submitButton('Проверить') ?>
        <div class="wrong-link">
            <?= Html::a('Зарегистрироваться', ['login/registration']) ?>
            <span>&nbsp; &nbsp; &nbsp; &nbsp;</span>
            <?= Html::a('Восстановить пароль', ['login/repassword']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
