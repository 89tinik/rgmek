<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="messages-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => $model->user_id])->label(false); ?>
    <?= $form->field($model, 'contract_id')->textInput(['disabled' => true, 'value' => $model->contract->number]) ?>
    <?= $form->field($model, 'subject_id')->textInput(['disabled' => true, 'value' => $model->subject->title]) ?>
    <?= $form->field($model, 'status_id')->textInput(['disabled' => true, 'value' => $model->status->status]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6, 'disabled' => true]) ?>

    <?php
    if (!empty($model->files)) {
        $files = json_decode($model->files);
        echo '<h3>Прикреплённые файлы</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    }
        echo $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('К списку', ['messages/index'],['class' => 'btn btn-success']) ?>
        <?= Html::a('Отозвать', ['messages/re-call', 'id'=>$model->id],['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
