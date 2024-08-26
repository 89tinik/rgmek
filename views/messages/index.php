<?php

use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Html;

$this->title = 'Messages';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="messages-index">
    <h1><?= Html::encode($this->title) ?></h1>



    <?php // Форма для фильтрации по диапазону дат ?>
    <div class="date-filter-form payment-filter white-box">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
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


    <div id="message-list">
        <?= $this->render('_list', ['dataProvider' => $dataProvider]) ?>
    </div>

    <div class="text-center">
        <button id="load-more" class="btn btn-primary">Показать ещё</button>
    </div>
    <p>
        <?= Html::a('Обратиться в РГМЭК', ['new-message/index'], ['class' => 'btn btn-success']) ?>
    </p>
</div>

<?php
$js = <<<JS
let page = 1;
let totalPages = {$dataProvider->pagination->pageCount};

$('#load-more').on('click', function () {
    page++;
    if (page >= totalPages) {
        $('#load-more').hide();
    }
    $.ajax({
        url: window.location.href,
        type: 'get',
        data: { page: page },
        success: function (data) {
            $('#message-list').append(data);
        }
    });
});
JS;

$this->registerJs($js);
