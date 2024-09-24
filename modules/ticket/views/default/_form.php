<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\FormatConverter;

/* @var $this yii\web\View */
/* @var $model app\models\Messages */
/* @var $form yii\widgets\ActiveForm */
/* @var $profileInfo array */


if (!empty($userModel->phone)) {
    $userPhone = $model->user->phone;
} else {
    if (is_array($profileInfo['PhoneCity']) && !isset($profileInfo['PhoneCity']['Value'])) {
        $userPhone = '';
        foreach ($profileInfo['PhoneCity'] as $arr) {
            if (empty($phoneCity)) {
                $userPhone = $arr['Value'];
            } else {
                $userPhone .= ', ' . $arr['Value'];
            }
        }
    } else {
        $userPhone = $profileInfo['PhoneCity']['Value'];
    }
}
// Преобразуем дату перед отображением
if ($model->published) {
    $model->published = Yii::$app->formatter->asDate($model->published, 'php:d.m.Y');
}
$publishedProperties = ['autocomplete' => 'off', 'readonly' => 'readonly'];
if (!empty($model->answer)) {
    $publishedProperties['disabled'] = true;
} else {
    $publishedProperties['id'] = 'admin_public';
}
$answerProperties = ['rows' => 6];
if (!empty($model->answer)) {
    $answerProperties['disabled'] = true;
}
$adminNumProperties = ['maxlength' => true];
if (!empty($model->admin_num)) {
    $adminNumProperties['disabled'] = true;
}

$currentStatusId = $model->status_id;
$statuses = \app\models\MessageStatuses::find()->all();

?>

<div class="messages-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
    <h2>Тело обращения</h2>

    <?= $form->field($model, 'contract_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->contract->number]) ?>
    <?= $form->field($model, 'subject_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->subject->title]) ?>
    <?= $form->field($model, 'user_id')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->user->full_name]) ?>
    <?= $form->field($model, 'user_email')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $model->user->email])->label('E-mail пользователя') ?>
    <?= $form->field($model, 'user_phone')->textInput(['class' => 'disabled', 'disabled' => true, 'value' => $userPhone])->label('Телефон пользователя') ?>
    <?= $form->field($model, 'contact_name')->textInput(['class' => 'disabled', 'disabled' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['class' => 'disabled', 'disabled' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['class' => 'disabled', 'disabled' => true]) ?>


    <?= $form->field($model, 'message')->textarea(['class' => 'disabled', 'rows' => 6, 'disabled' => true]) ?>

    <?php
    if (!empty($model->files)) {
        $files = json_decode($model->files);
        echo '<h3>Пользовательские файлы</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    }
    ?>
    <h2>Обработка обращения</h2>

    <?= $form->field($model, 'status_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($statuses, 'id', 'status'),
        [
            'options' => \yii\helpers\ArrayHelper::map($statuses, 'id', function ($status) use ($currentStatusId) {
                return $status->id < $currentStatusId ? ['disabled' => true] : [];
            }),
            'class' => 'form-control styler select__default'
        ]
    ) ?>

    <?= $form->field($model, 'published')->textInput($publishedProperties) ?>

    <?= $form->field($model, 'admin_num')->textInput($adminNumProperties) ?>

    <?= $form->field($model, 'answer')->textarea($answerProperties) ?>

    <?php
    if (!empty($model->answer_files)) {
        $files = json_decode($model->answer_files);
        echo '<h3>Прикреплённые файлы</h3><ul>';
        foreach ($files as $file) {
            echo '<li>' . Html::a(basename(mb_convert_encoding($file, 'UTF-8', 'auto')), ['/' . $file], ['target' => '_blank']) . '</li>';
        }
        echo '</ul>';
    } else {
        echo $form->field($model, 'answerFilesUpload[]')->fileInput(['multiple' => true]);
    }
    ?>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
