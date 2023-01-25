<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index grid-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?php //= Html::a('Новая страница', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            ['attribute' => 'content', 'value' => function ($model) {
                return mb_strimwidth(strip_tags($model->content), 0, 200, '...');
            }],


            ['class' => 'yii\grid\ActionColumn',
                'header'=>'#',
                'headerOptions' => ['width' => '80'],
                'template' => '{update}',

            ],
        ],
    ]); ?>


</div>
