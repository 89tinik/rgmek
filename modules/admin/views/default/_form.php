<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?//= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password')->textInput() ?>

    <?= $form->field($model, 'blocked')->radioList(['0' => 'Нет', '1' => 'Да'],
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

<!--    --><?//= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'inn')->textarea(['rows' => 6]) ?>

<!--    --><?//= $form->field($model, 'kpp')->textarea(['rows' => 6]) ?>

<!--    --><?//= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'contract')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'id_db')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'temp')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'with_date')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'by_date')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
