<?php

/* @var $this yii\web\View */

/* @var $registerForm */

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

        <div class="title">Регистрация</div>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            echo Yii::$app->session->getFlash('error');
        }
        ?>
        <?= $form->field($registerForm, 'inn')->textInput(['placeholder' => 'ИНН']) ?>
        <?= $form->field($registerForm, 'contract')->textInput(['placeholder' => '№ договора']) ?>
        <?= $form->field($registerForm, 'password')->passwordInput(['placeholder' => 'Пароль']) ?>
        <?= $form->field($registerForm, 'rePassword')->passwordInput(['placeholder' => 'Повторите пароль']) ?>
<?php
//$registerForm->method = '1';
?>
        <?= $form->field($registerForm, 'method')->radioList(['0'=>'E-mail', '1'=>'Телефон'],  ['itemOptions' => ['class' => 'styler']]); ?>
        <?= $form->field($registerForm, 'email')->textInput(['placeholder' => 'E-mail', 'class'=>'email form-control']) ?>
        <?= $form->field($registerForm, 'phone')->textInput(['placeholder' => 'Телефон', 'class'=>'phone form-control']) ?>
        <?= Html::submitButton('Регистрация') ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

