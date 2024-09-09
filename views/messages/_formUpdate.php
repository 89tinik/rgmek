<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="messages-form">
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            echo '<div class="form-message">' . Yii::$app->session->getFlash('success') . '</div>';
        }
        ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>

    <?php if (!empty($model->created)){?>
        <?= $form->field($model, 'created')->textInput(['class' => 'disabled', 'disabled' => true, 'value'=>Yii::$app->formatter->asDate($model->created, 'php:d.m.Y')]) ?>

    <?php } ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => $model->user_id])->label(false); ?>
    <?= $form->field($model, 'contract_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->contract->number]) ?>
    <?= $form->field($model, 'subject_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->subject->title]) ?>
    <?= $form->field($model, 'status_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->status->status]) ?>

    <?= $form->field($model, 'message')->textarea(['class' => 'disabled', 'rows' => 6, 'disabled' => true]) ?>

    <?php
    if (!empty($model->files)) {
        $files = json_decode($model->files);
        echo '<h3>Прикреплённые файлы</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    }
        echo $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true, 'class' => 'input-file']);

    ?>
    <?= $form->field($model, 'answer')->textarea(['class' => 'disabled', 'rows' => 6, 'disabled' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success hidden']) ?>
        <?= Html::a('К списку', ['messages/index'],['class' => 'btn btn-success message-btn']) ?>
        <?= Html::a('Отозвать', ['messages/re-call', 'id'=>$model->id],['class' => 'btn btn-success message-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
