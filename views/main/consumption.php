<?php

/* @var $this yii\web\View */
/* @var $objectsData */

/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Информация об электропотреблении |  ЛК РГМЭК';
\app\assets\ConsumptionAssets::register($this);

?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Информация об электропотреблении по договору</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
    </div>
</div>
<!---->
<div class="arrear-left top-chart border-box">

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>
</div>


<div class="arrear-right">
    <div class="payment-form white-box">
        <div class="invoice-table">
            <table>
                <tbody>
                <?php
                if (isset($objectsData['Total']['Line'])) {
                    if (isset($objectsData['Total']['Line']['Date'])) {
                        echo $this->render('_consumptionTop', [
                            'month' => $objectsData['Total']['Line']
                        ]);
                    } else {
                        foreach ($objectsData['Total']['Line'] as $arr) {
                            echo $this->render('_consumptionTop', [
                                'month' => $arr
                            ]);
                        }
                    }

                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!---->
<div class="clear"></div>

<?php
$tooltip = <<<TOOLTIP
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
TOOLTIP;
$xMonth = "xAxis: {
            categories: [
                'Янв',
                'Фев',
                'Мар',
                'Апр',
                'Май',
                'Июн',
                'Июл',
                'Авг',
                'Сен',
                'Окт',
                'Ноя',
                'Дек'
            ],
            crosshair: true
        },";
$js = "Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        $xMonth
        yAxis: {
            min: 0,
            title: {
                text: 'кВт'
            }
        },
        $tooltip
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }]
    });";
$this->registerJs($js);

?>


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
    $objectOptionArr = [];
    foreach ($objectsData['Objects']['Line'] as $object) {
        $objectOptionArr[$object['UIDObject']] = $object['FullName'];
    }
    ?>
    <div class="group large">
        <div class="label">Выбрать объект:</div>
        <?= $form->field($model, 'uidobject', ['template' => '{input}'])->dropdownList(
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

<?php
if (isset($objectsData['Object'])) {
    if (isset($objectsData['Object']['FullName'])) {
        echo $this->render('_consumptionObject', [
            'object' => $objectsData['Odject']
//            'currentTU' => $currentTU,
//            'model' => $model
        ]);
    } else {
        foreach ($objectsData['Object'] as $arr) {
            echo $this->render('_consumptionObject', [
                'object' => $arr
//                'currentTU' => $currentTU,
//                'model' => $model
            ]);
        }
    }

}
//?>
