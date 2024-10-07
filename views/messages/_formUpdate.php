<?php

use app\models\MessageStatuses;
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

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= Html::a('Обращение.pdf', '#', ['class' => ' ajax-pdf-update', 'message' => $model->id]) ?>

    <?php
    if (!empty($model->files)) {
        $files = json_decode($model->files);
        echo '<h3>Прикреплённые файлы</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    }
    if ($model->status_id < MessageStatuses::SUCCESS) {
        echo $form->field($model, 'filesUpload[]')->fileInput(['multiple' => true, 'class' => 'input-file']);
    }

    ?>
    <ul id="filesList"></ul>
    <?= $form->field($model, 'answer')->textarea(['class' => 'disabled', 'rows' => 6, 'disabled' => true]) ?>
    <?php
    if (!empty($model->answer_files)) {
        $files = json_decode($model->answer_files);
        echo '<h3>Прикреплённые файлы ответа</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    }

    ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success hidden']) ?>
        <?= Html::a('К списку', ['messages/index'], ['class' => 'btn btn-success message-btn']) ?>
        <?php if ($model->status_id < MessageStatuses::SUCCESS) { ?>
            <?= Html::a('Отозвать', ['messages/re-call', 'id' => $model->id], ['class' => 'btn btn-success message-btn']) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>