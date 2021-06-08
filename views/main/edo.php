<?php

/* @var $this yii\web\View */
/* @var $installESForm */
/* @var $buttonText */
/* @var $invoiceEmail */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Электронный документооборот |  ЛК РГМЭК';

?>
<div class="page-heading">
    <h1 class="title">Электронный документооборот</h1>
</div>

<div class="box-tabs tabs">
    <div class="tab-menu">
        <ul>
            <li class="active"><a href="#doc_tab_1" class="tab-btn">Электронный документооборот</a></li>
            <li><a href="#doc_tab_2" class="tab-btn">Счета по эл. почте</a></li>
        </ul>
    </div>
    <div class="tab-items">
        <div class="tab-item" id="doc_tab_1" style="display: block;">
            <div class="text-box">
                <h3>Как перейти на электронный <br/>документооборот с ООО “РГМЭК”</h3>
                <ol>
                    <li>Обратитесь в СКБ “Контур” (Диадок) или ООО “Тензор” (СБИС) для подключения системы электронного документооборота (ЭДО)</li>
                    <li><a href="#" class="link-popup-contract-edo">Сформируйте Соглашение об ЭДО</a></li>
                    <li>
                        Предоставьте заполненное соглашение в ООО “РГМЭК” одним из способов:
                        <ul>
                            <li>принесите оригинал в двух экземплярах в офис ООО “РГМЭК”</li>
                            <li>отправьте Вашей системой ЭДО с ЭЦП</li>
                        </ul>
                    </li>
                    <li>
                        Начните обмен документами через системы ЭДО (после того как соглашение будет подписано со стороны ООО “РГМЭК”)
                    </li>
                </ol>
                <p>
                    В результате электронный документооборот (ЭДО) позволит Вам оперативно получать платежные докуметы
                    ООО “РГМЭК”:
                </p>
                <ul>
                    <li>Счет-фактуру</li>
                    <li>Акт приема - передачи</li>
                    <li>Счет на оплату</li>
                    <li>Детализацию счета</li>
                    <li>Деловую переписку по договору энергоснабжения</li>
                </ul>
                <p>
                    Если Вы уже являетесь участником ЭДО, Вы можете уточнить информацию о возможности перехода на ЭДО
                    следующими способами:
                </p>
                <ul>
                    <li>в разделе <a href="#">написать обращение</a></li>
                    <li>по телефону 8(4912) 21-74-04</li>
                    <li>в офисе на ул. Радищева, д.61, каб. №1 (зал обслуживания небытовых потребителей)</li>
                </ul>
            </div>
        </div>
        <div class="tab-item" id="doc_tab_2" style="display: none;">
            <div class="text-box">
                <h3>Счета по эл. почте</h3>
                <p>
                    Для лиц, не являющихся учасниками ЭДО, доступна возможность получения документов по договору
                    энергоснобжения с ООО “РГМЭК” на адрес электронной почты.
                </p>
                <p>
                    Услуга предоставляется бесплатно.
                </p>
                <p>
                    На Ваш адрес электронной почты Вы будете получать:
                </p>
                <ul>
                    <li>Счет на оплату</li>
                    <li>Детализацию счета</li>
                    <li>Деловую переписку по договору энергоснобжения</li>
                </ul>
                <p>
                    <br/>
                </p>
            </div>
            <div class="subscribe-form">
                <div class="group">
                    <label>
                        <input type="radio" class="styler" name="email_radio" value="0" <?php if(!$invoiceEmail):?>checked="checked"<?endif;?>/>
                        Я согласен получать документы в бумажном виде только по запросу
                    </label>
                </div>
                <div class="group">
                    <label>
                        <input type="radio" class="styler" name="email_radio" value="1" <?php if($invoiceEmail):?>checked="checked"<?endif;?>/>
                        Я хочу получать документы на адрес электронной почты
                    </label>
                </div>
                <div class="subscribe-group">

                    <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'action' => ['main/edo', '#' => 'doc_tab_2'],
                        'fieldConfig' => [
                            'template' => "{input}{error}",
                            'options' => [
                                'tag' => false
                            ],
                        ]
                    ]); ?>
                    <?php
                    if (Yii::$app->session->hasFlash('success')) {
                        echo '<div class="success">'.Yii::$app->session->getFlash('success').'</div>';
                    }
                    if (Yii::$app->session->hasFlash('error')) {
                        echo  '<div class="error">'.Yii::$app->session->getFlash('error').'</div>';
                    }
                    ?>
                    <div class="input-wrap">
                    <?= $form->field($installESForm, 'user')->hiddenInput(['value' => Yii::$app->user->identity->id_db]); ?>
                    <?= $form->field($installESForm, 'email')->textInput(['placeholder' => 'E-mail', 'autofocus' => true, 'type' => 'email']) ?>
                    </div>
                    <?= Html::submitButton($buttonText, ['class' => 'btn submit-btn']) ?>

                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>
    </div>
</div>
