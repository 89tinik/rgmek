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
        <div class="title"><?= Yii::$app->session->getFlash('title') ?></div>
        <div class="message">
            <?php
            if (Yii::$app->session->hasFlash('message')) {
                echo Yii::$app->session->getFlash('message');
            }
            if (Yii::$app->session->hasFlash('error')) {
                echo '<br/>' . Yii::$app->session->getFlash('error');
            }
            ?>
        </div>

        <?= $form->field($verificationForm, 'code')->textInput(['placeholder' => 'Код', 'autofocus' => true]) ?>
        <?= Html::submitButton('Проверить') ?>
        <p>Если код не пришел в течение 1 минуты - повторно <a href="#" class="resend">отправьте запрос</a>.</p>
        <div class="wrong-link">
            <?= Html::a('Зарегистрироваться', ['login/registration']) ?>
            <span>&nbsp; &nbsp; &nbsp; &nbsp;</span>
            <?= Html::a('Восстановить пароль', ['login/repassword']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
