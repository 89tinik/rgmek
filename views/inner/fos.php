<?php

/* @var $this yii\web\View */

/* @var $result */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Написать обращение | ЛК РГМЭК';
?>
<div class="page-heading">
    <h1 class="title">Написать обращение</h1>
</div>
<div class="h-titles">
    <div class="h-subtitle">
        Заполните поля формы.
    </div>
</div>

<div class="anketa-box">

    <div class="box-tabs tabs tabs-white">

        <div class="tab-items">
            <div class="tab-item">
                <div class="c-form anketa-form">
                    <?php
                    if (Yii::$app->session->hasFlash('success')) {
                        echo Yii::$app->session->getFlash('success');
                    }
                    if (Yii::$app->session->hasFlash('error')) {
                        echo Yii::$app->session->getFlash('error');
                    }
                    ?>
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'id' => 'feedbackForm',
                            'enctype' => 'multipart/form-data'
                        ],
                        'fieldConfig' => [
                            'template' => '
                                    <div class="group">
                                        <div class="label">{label}:</div>
                                        <div class="field">
                                            {input}
                                        {error}
                                        </div>
                                    </div>',
                            'options' => [
                             //   'tag' => false
                            ]
                        ]
                    ]); ?>

                    <div class="group-row">


                        <div class="type-anketa-fields type-fields-entity">
                            <!--div class="group-col full">
                                <div class="c-title">Бланк для заполнения: Юр. лицо</div>
                            </div-->

                            <div class="group-col">
                                <?= $form->field($model, 'name') ?>
                                <?= $form->field($model, 'patronymic')?>
                                <?= $form->field($model, 'surname')?>

                                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                                    'mask' => '89999999999',
                                ])->textInput(['class' => 'phone form-control']) ?>
                                <?= $form->field($model, 'email') ?>
                                <?=$form->field($model, 'polit',['template' => ' <div class="group">
 <div class="wrap-polit-checkbox">{input}</div>
 <p class="polit-label">Нажимая кнопку «Регистрация», я принимаю условия Пользовательского соглашения и даю согласие ООО «РГМЭК» на обработку моих персональных данных на условиях, определенных '.Html::a('Пользовательским соглашением', ['/'],['class'=>'ploader']).'.</p>
 </div>'])->checkbox([ 'value' => '1', 'checked ' => true, 'class'=>'styler polit', 'label' => null]);?>


                            </div>

                            <div class="group-col">
                                <?= $form->field($model, 'contract')->widget(\yii\widgets\MaskedInput::className(), [
                                    'mask' => '9{1,}',
                                ])->textInput(['autocomplete' => 'off']) ?>

                                <?= $form->field($model, 'entity') ?>
                                <?= $form->field($model, 'subject')->dropdownList([
                                    'Электронный документооборот'=>'Электронный документооборот',
                                    'Прибор учета, заявка на замену'=>'Прибор учета, заявка на замену',
                                    'Заключить/изменить договор'=>'Заключить/изменить договор',
                                    'Взаиморасчеты, показания'=>'Взаиморасчеты, показания',
                                    'Задолженность, ограничения'=>'Задолженность, ограничения',
                                    'Предложения и жалобы по качеству эл.энергии'=>'Предложения и жалобы по качеству эл.энергии',
                                    'Обслуживание и общие вопросы'=>'Обслуживание и общие вопросы'
                                ],
                                    [
                                            'class'=>'styler select__default',
                                        'prompt'=>'Выберите...'

                                    ]); ?>


                                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
                                <?= $form->field($model, 'file[]')->fileInput(['multiple'=>'multiple']) ?>
                            </div>

                        </div>


                    </div>
                    <div class="group-row">
                        <div class="group-col">
                            <?= Html::submitButton('Отправить', ['class' => 'btn full']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>

</div>
