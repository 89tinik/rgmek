<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика сообщений';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="statistics-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="date-filter-form payment-filter white-box">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'fieldConfig' => [
                'template' => '<div class="field">
                                <div class="value">
                                    <span>{label}</span>
                                     {input}
                                     {error}
                                </div>
                            </div>',
                'options' => [
                    'tag' => false
                ]
            ]
        ]); ?>
        <div class="group large">
            <div class="label">Выбрать период:</div>
            <?= $form->field($searchModel, 'date_from')->input('text', ['placeholder' => 'От'])->label('с') ?>
            <?= $form->field($searchModel, 'date_to')->input('text', ['placeholder' => 'До'])->label('по') ?>

            <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'subject_id',
                'label' => 'Тема',
                'value' => function ($model) {
                    return $model->subject->title; // Предположим, что у вас есть связь с таблицей темы
                },
            ],
            [
                'attribute' => 'message_count',
                'label' => 'Количество сообщений',
                'value' => function ($model) {
                    return $model->message_count;
                },
            ],
        ],
    ]); ?>

</div>
