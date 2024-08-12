<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageThemesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Message Themes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-themes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать тему', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'title',
                'headerOptions' => ['width' => '500']
            ],
            ['attribute' => 'content',
                'value' => function ($model) {
                return mb_strimwidth(strip_tags($model->content), 0, 200, '...');
            }],


            ['class' => 'yii\grid\ActionColumn',
                'header'=>'#',
                'headerOptions' => ['width' => '40'],
                'template' => '{update} {delete}',

            ],
        ],
    ]); ?>


</div>
