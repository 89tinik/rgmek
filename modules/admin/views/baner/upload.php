<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UploadForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baner-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'path')->fileInput(['class'=>'form-control']) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disable')->radioList(['0' => 'Нет', '1' => 'Да'],
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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end() ?>

</div>
