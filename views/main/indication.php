<?php
/* @var $this yii\web\View */
/* @var $result */
/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->title = 'Передать показания | ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Передать показания приборов учета</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
        <p style="color:red;">Срок передачи показаний приборов учета  с <?=$result['Withdate']?> по <?=$result['Bydate']?> число текущего месяца.</p>
    </div>
</div>

<div class="objects-items uid-d"  data-uid="<?=\Yii::$app->user->identity->id_db?>">
    <?php
    if (Yii::$app->session->hasFlash('success')) {
        echo Yii::$app->session->getFlash('success');
    }
    if (Yii::$app->session->hasFlash('error')) {
        echo Yii::$app->session->getFlash('error');
    }
    ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'attachFormPu',
            'enctype' => 'multipart/form-data'
        ],
        'fieldConfig' => [
            //'template' => '{input}',
            'options' => [
                //   'tag' => false
            ]
        ]
    ]);

    ?>

    <?= $form->field($model, 'puid')->textInput(['class'=>'puidInput']) ?>
    <?= $form->field($model, 'photo')->fileInput(['accept'=>'.png, .jpg, .jpeg']) ?>
    <?= $form->field($model, 'time')->fileInput(['accept'=>'.xls, .xlsx']) ?>
    <?= Html::submitButton('Отправить', ['class' => 'btn full']) ?>
    <?php ActiveForm::end(); ?>
    <?php
    if (isset($result['Object'])){
        if (isset($result['Object']['Name'])){
            echo $this->render('_objectIndicationItem', [
                'object' => $result['Object'],
                'UIDContract' => $result['UIDContract'],
                'one' => true,
                'model' => $model
            ]);
        } else {
            foreach ($result['Object'] as $arr) {
                echo $this->render('_objectIndicationItem', [
                    'object' => $arr,
                    'UIDContract' => $result['UIDContract'],
                    'one' => false,
                    'model' => $model
                ]);
            }
        }

    }
    ?>


</div>
