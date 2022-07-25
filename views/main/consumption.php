<?php

/* @var $this yii\web\View */
/* @var $objectsData */
/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Информация об электропотреблении |  ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Информация об электропотреблении по договору</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
    </div>
</div>

<div class="payment-filter white-box">
    <?php
    if (Yii::$app->session->hasFlash('success')) {
        echo Yii::$app->session->getFlash('success');
    }
    if (Yii::$app->session->hasFlash('error')) {
        echo Yii::$app->session->getFlash('error');
    }
    ?>
    <?php $form = ActiveForm::begin([
        'action' => [''],
        'method' => 'get',
        'options' => [
//            'method' => 'get'
        ],
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
    ]);

    ?>



    <?= $form->field($model, 'uidtu', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'uid', ['template' => '{input}'])->hiddenInput(); ?>
    <?php
    $objectOptionArr=[];
    foreach ($objectsData['Objects']['Line'] as $object){
        $objectOptionArr[$object['UIDObject']]=$object['FullName'];
    }
    ?>
    <div class="group large">
        <div class="label">Выбрать объект:</div>
        <?= $form->field($model, 'uidobject',['template' => '{input}'])->dropdownList(
                $objectOptionArr,
            [
                'class' => 'styler select__default',
                'prompt' => 'Все объекты'

            ]); ?>

    </div>
    <div class="group large">
        <div class="label">Выбрать период:</div>
        <?= $form->field($model, 'withdate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'from_dialog']) ?>
        <?= $form->field($model, 'bydate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'to_dialog']) ?>

        <?= Html::submitButton('Сформировать', ['class' => 'btn submit-btn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!--<h1 class="object-name-history">--><?//= $result['FullName'] ?><!--</h1>-->
<?php
//if (!empty($result['CalculationAlgorithm'])) {
//    echo '<h3 class="colculation-history">' . $result['CalculationAlgorithm'] . '</h3>';
//}
//?>
<!---->
<?php
//if (isset($result['PU'])) {
//    if (isset($result['PU']['Name'])) {
//        echo $this->render('_historyIndicationItem', [
//            'pu' => $result['PU'],
//            'currentTU' => $currentTU,
//            'model' => $model
//        ]);
//    } else {
//        foreach ($result['PU'] as $arr) {
//            echo $this->render('_historyIndicationItem', [
//                'pu' => $arr,
//                'currentTU' => $currentTU,
//                'model' => $model
//            ]);
//        }
//    }
//
//}
//?>
