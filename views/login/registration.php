<?php

/* @var $this yii\web\View */

/* @var $registerForm */

/* @var $kpp */

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;

$this->title = 'Регистрация в личном кабинете небытового потребителя';
?>

<!-- Login Form -->
<div class="login-form-fw">
    <div class="registrText">
        <p>Личный кабинет «Бизнес Электроснабжение» предназначен для небытовых потребителей электроэнергии гарантирующего поставщика. </p>

        <p>Пользуясь личным кабинетом, Вы сможете: </p>
        <ul>
        <li><strong>Передавать показания</strong>. История показаний и графики потребления электроэнергии будут полезны Вам при планировании расходов.</lI>
        <li><strong>Получать счета на оплату</strong>. Все счета будут всегда у Вас под рукой в электронном виде.</lI>
        <li><strong>Контролировать состояние расчетов</strong>. Актуальная информация о дебиторской или кредиторской задолженности за электроэнергию будет доступна Вам в любое время.</lI>
        <li><strong>Оплачивать услуги быстро и без комиссий</strong>. Сервис оплаты банковской картой. Больше никаких очередей и лишних трат. Оплачивайте прямо со своего смартфона или компьютера.</lI>
        </ul>
        <p>Структура Личного кабинета простая и понятная. Зарегистрируйтесь и почувствуйте все преимущества! </p>
        <p><a href="/web/doc/rukovodstvo-po-registracii.pdf" target="_blank">Инструкция по регистрации</a></p>


    </div>
    <div style="clear:both;"></div>
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'registerForm',
                'class' => 'c-form'
            ],
            'fieldConfig' => [
                'template' => "
						<div class=\"field \">
							{input}
							{error}
						</div>"
            ]
        ]); ?>

        <div class="title"><?= $this->title; ?></div>
        
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
        ])->textInput(['placeholder' => 'ИНН', 'autocomplete' => 'off']) ?>
        <?= $form->field($registerForm, 'contract')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,}',
        ])->textInput(['placeholder' => '№ договора', 'autocomplete' => 'off']) ?>
        <?= $form->field($registerForm, 'password', ['template' => '<div class="field ">
							{input}
							<div class="eye left"></div>	
							<div class="label-info"></div>
                            <div class="label-error" style="display: none;">
                                Запомните или сохраните пароль. Он будет использоваться при входе в личный кабинет.
                                <div class="close"></div>
                            </div>
							{error}
						</div>'])->passwordInput(['placeholder' => 'Пароль', 'autocomplete' => 'off']) ?>
        <?= $form->field($registerForm, 'rePassword', ['template' => '<div class="field ">
							{input}
							<div class="eye "></div>
							{error}
						</div>'])->passwordInput(['placeholder' => 'Повторите пароль', 'autocomplete' => 'off']) ?>

        <?= $form->field($registerForm, 'method')->radioList(['0' => 'E-mail', '1' => 'Телефон'], ['itemOptions' => ['class' => 'styler']]); ?>
        <?= $form->field($registerForm, 'email', ['template' => '<div class="field ">
							{input}
							<div class="label-info"></div>
                            <div class="label-error" style="display: none;">
                                На этот адрес придет код для подтверждения регистрации
                                <div class="close"></div>
                            </div>
							{error}
						</div>'])->textInput(['placeholder' => 'E-mail', 'class' => 'email form-control']) ?>
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
        ])->textInput(['placeholder' => 'Телефон', 'class' => 'phone form-control']) ?>
        <?= Html::submitButton('Регистрация') ?>

        <!--<div  class="instruction"><a href="/web/doc/rukovodstvo-po-registracii.pdf" target="_blank">Инструкция по регистрации</a></div>-->
        <p class="register-polit"> Нажимая кнопку «Регистрация», я принимаю условия Пользовательского соглашения и даю
            согласие ООО «Р-Энергия» на обработку моих персональных данных на условиях, определенных
            <?= Html::a('Пользовательским соглашением', ['login/information'],['target'=>'_blank']) ?>.</p>
        <?php echo $this->render('_app');?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

