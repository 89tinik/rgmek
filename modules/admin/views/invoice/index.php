<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index grid-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'created_at',
            'sum',
            'order_id',
            [
                'label' => 'Договор',
                'attribute' => 'contract',
                'value' => 'receipt.indenture.number'
            ],
            [
                'label' => 'Логин пользователя',
                'attribute' => 'user_login',
                'value' => 'user.username'
            ],
            [
                'label' => 'Наименование контрагента',
                'attribute' => 'user_name',
                'value' => 'user.full_name'
            ],
            'status',
            //'created_at',
            //'method',
            //'orderId',
            //'remote_id',
            //'data:ntext',
            //'url:url',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


    <?php Pjax::end(); ?>
</div>
