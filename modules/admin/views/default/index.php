<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index grid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!---->
    <!--    <p>-->
    <!--        --><? //= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    <!--    </p>-->

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => " ",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'username',
//            'password',
//            'auth_key',
            'inn:ntext',
            'kpp:ntext',
            'email:email',
            'phone',
            [
                'label' => 'Договор',
                'attribute' => 'contract',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
            ],
//            'id_db',
//            'temp',
            [
                'label' => 'Наименование',
                'attribute' => 'full_name',
            ],
//            'with_date',
//            'by_date',
            ['attribute' => 'blocked', 'value' => function ($model) {
                return ($model->blocked == 1) ? 'Да' : 'Нет';
            }],

            ['class' => 'yii\grid\ActionColumn',
                'header' => '#',
                'headerOptions' => ['width' => '80'],
                'template' => '{as-user} {update} {delete}',
                'buttons' => [
                    'as-user' => function ($url, $model, $key) {
                        return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>', $url, ['data-pjax' => 0]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
