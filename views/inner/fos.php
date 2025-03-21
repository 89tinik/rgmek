<?php

/* @var $this yii\web\View */

/* @var $result */
/* @var $contracts */
/* @var $model */
/* @var $itemThemes */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Написать обращение | ЛК Р-Энергия';
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
                    <div class="form-message">
                    <?php
                    if (Yii::$app->session->hasFlash('success')) {
                        echo Yii::$app->session->getFlash('success');
                    }
                    if (Yii::$app->session->hasFlash('error')) {
                        echo Yii::$app->session->getFlash('error');
                    }
                    ?>
                    </div>
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
                                <?=$form->field($model, 'user',['template' => '{input}'])->hiddenInput(['value' => \Yii::$app->controller->userName]);?>
                                <?=$form->field($model, 'contracts',['template' => '{input}'])->hiddenInput(['value' => $contracts]);?>
                                <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя']) ?>

                                <div class="radio-items type-answer">
                                    <?php

                                    $model->type_answer = (!empty($model->type_answer)) ? $model->type_answer : 'phone';
                                    ?>
                                    <?= $form->field($model, 'type_answer')->radioList(['phone' => 'Телефон', 'email' => 'E-mail'],
                                        [
                                            'item' => function ($index, $label, $name, $checked, $value) {
                                                $checked = ($checked) ? 'checked="checked"' : '';
                                                $return = '<div class="radio-item"> <label>';
                                                $return .= '<input type="radio" class="styler" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
                                                $return .= ucwords($label);
                                                $return .= '</label></div>';

                                                return $return;
                                            }
                                        ]) ?>
                                </div>


                                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                                    'mask' => '89999999999',
                                ])->textInput(['class' => 'phone']) ?>
                                <?= $form->field($model, 'email')->textInput(['class' => 'email']) ?>



                            </div>

                            <div class="group-col">

                                <?= $form->field($model, 'subject')->dropdownList($itemThemes,
                                    [
                                        'class' => 'styler select__default',
                                        'prompt' => 'Выберите...'

                                    ]); ?>


                                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
                                <?= $form->field($model, 'file[]')->fileInput(['multiple' => 'multiple', 'class' => 'fos-files']) ?>
                                <?= Html::submitButton('Отправить', ['class' => 'btn full']) ?>
                            </div>

                        </div>


                    </div>
                    <div class="group-row">
                        <div class="group-col">

                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>

</div>
