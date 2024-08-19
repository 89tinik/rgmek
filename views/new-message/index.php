<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выберите тему сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-themes-index">

    <h1><?= Html::encode($this->title) ?></h1>




    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'showHeader' => false,
        'columns' => [
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a(Html::encode($data->title), ['create', 'id' => $data->id]);
                },
            ],
        ],
    ]); ?>


</div>
