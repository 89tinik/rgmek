<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Baner */

$this->title = 'Редактировать банер: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Baners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="baner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="baner-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
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
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>



        <?php ActiveForm::end() ?>

    </div>

</div>
