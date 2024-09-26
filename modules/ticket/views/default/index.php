<?php

use app\models\MessageStatuses;
use app\models\MessageThemes;
use app\models\Theme;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--        --><?php //= Html::a('Create Messages', ['create'], ['class' => 'btn btn-success']) ?>

    <?php
    if (Yii::$app->session->hasFlash('success')) {
        echo '<div class="form-message">' . Yii::$app->session->getFlash('success') . '</div>';
    }
    ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'enableSorting' => false,
                'filter' => false
            ],
            [
                'attribute' => 'created',
                'label' => 'Дата создания',
                'enableSorting' => false,
                'filter' => Html::activeTextInput($searchModel, 'created', [
                'class' => 'form-control',
                'readonly' => true, // Поле будет только для чтения
            ]),
            ],
            [
                'attribute' => 'admin_num',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'user_name',
                'label' => 'Пользователь',
                'value' => 'user.full_name',
                'filter' => Html::activeTextInput($searchModel, 'user_name', ['class' => 'form-control']), // Поле ввода для фильтрации
            ],
            [
                'attribute' => 'subject_id',
                'label' => 'Тема',
                'value' => 'subject.title',
                'enableSorting' => false,
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'subject_id',
                    ArrayHelper::map(MessageThemes::find()->all(), 'id', 'title'),
                    [
                        'class' => 'form-control styler select__default', // Добавляем классы здесь
                        'prompt' => 'Выберите тему', // Добавляем пустой элемент в начале списка (по желанию)
                    ]
                ), // Формирование выпадающего списка

            ],
            [
                'attribute' => 'contract_number',
                'label' => 'Договор',
                'value' => 'contract.number',
                'filter' => Html::activeTextInput($searchModel, 'contract_number', ['class' => 'form-control']), // Поле ввода для фильтрации
            ],
            [
                'attribute' => 'status_id',
                'label' => 'Статус',
                'value' => 'status.status',
                'enableSorting' => false,
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status_id',
                    ArrayHelper::map(MessageStatuses::find()->all(), 'id', 'status'),
                    [
                        'class' => 'form-control styler select__default', // Добавляем классы здесь
                        'prompt' => 'Выберите статус', // Добавляем пустой элемент в начале списка (по желанию)
                    ]
                ), // Формирование выпадающего списка

            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'#',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
