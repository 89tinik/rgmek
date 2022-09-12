<?php

/* @var $this yii\web\View */
/* @var $objectsData */

/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Информация об электропотреблении |  ЛК РГМЭК';
\app\assets\ConsumptionAssets::register($this);
$chartDataArr = array_fill(1, 12, 0);
$chartYarsDataArr = [];
$seriesArr = [];
?>
<style>
.invoice-table.consumption tr th{
	text-align:left;
}
.invoice-table.consumption table td .price{
	font-size:14px;
}
.consumption .jq-selectbox__select-text{ 
	font-size:14px;
}
.consumption label{
    padding-left:0;
}
a.btn.small.colculation-popup-link{
    margin-top:0;
}
.payment-filter.consumption{
    max-width:100%;
}
@media screen and (max-width: 1200px){
    .invoice-table.consumption table tr td:last-child{
        clear:unset;
        width:auto;
    } 
    .invoice-table.consumption table tr td{
        text-align:left;
        float:left;
    } 
    .invoice-table.consumption.month table tr td{
        width:33%;
    } 
}
</style>
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
        <div class="invoice-table consumption">
            <table>
                <tbody>
                 <tr>
                     <td>Месяц</td>
                     <td>Расход, кВт ч</td>
                 </tr>   
                    
                    
                <?php
                if (isset($objectsData['Total']['Line'])) {
                    if (isset($objectsData['Total']['Line']['Date'])) {
                        $dateArr = explode('.', $objectsData['Total']['Line']['Date']);
                        $chartDataArr[intval($dateArr['1'])] = $objectsData['Total']['Line']['Volume'];
                        $seriesArr[] = "{name: '".$objectsData['Total']['Line']['Year']."', data: [".implode(',', $chartDataArr)."]}";
                        echo $this->render('_consumptionTop', [
                            'month' => ['Month'=>$objectsData['Total']['Line']['Month'], 'Year'=> [$objectsData['Total']['Line']['Year']], 'Volume'=>[$objectsData['Total']['Line']['Volume']]]
                        ]);
                    } else {
                        $tableDataArr = array_fill(1, 12, ['Month'=>'', 'Year'=> [], 'Volume'=>[]]);
                        $currentYear = $objectsData['Total']['Line'][0]['Year'];
                        foreach ($objectsData['Total']['Line'] as $arr) {
                            if ($currentYear != $arr['Year']) {
                                $seriesArr[] = "{name: '".$currentYear."', data: [".implode(',', $chartDataArr)."]}";
                                $chartDataArr = array_fill(1, 12, 0);
                                $currentYear = $arr['Year'];
                            }
                            $dateArr = explode('.', $arr['Date']);
                            $chartDataArr[intval($dateArr['1'])] = $arr['Volume'];
                            $tableDataArr[intval($dateArr['1'])]['Month']= $arr['Month'];
                            $tableDataArr[intval($dateArr['1'])]['Year'][]= $arr['Year'];
                            $tableDataArr[intval($dateArr['1'])]['Volume'][]= $arr['Volume'];
                        }
                        $seriesArr[] = "{name: '".$currentYear."', data: [".implode(',', $chartDataArr)."]}";
                    }

                    if (isset($tableDataArr)){
                        foreach($tableDataArr as $month){
                            if (!empty($month['Month'])){
                                echo $this->render('_consumptionTop', [
                                    'month' => $month
                                ]);
                            }

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
                '<td style="padding:0"><b>{point.y:.1f} кВт ч</b></td></tr>',
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
$series = implode(',', $seriesArr);
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
                text: 'кВт ч'
            }
        },
        $tooltip
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [$series]
    });";
$this->registerJs($js);

?>


<div class="payment-filter white-box consumption">
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
    if (isset($objectsData['Objects'])){
        if (isset($objectsData['Objects']['Line']['UIDObject'])){
            $objectOptionArr[$objectsData['Objects']['Line']['UIDObject']] = $objectsData['Objects']['Line']['FullName'];
        } else {
            foreach ($objectsData['Objects']['Line'] as $object) {
                $objectOptionArr[$object['UIDObject']] = $object['FullName'];
            }
        }
    }

    ?>
    <div class="group large">
        <div class="label">Выбрать объект:</div>
        <?= $form->field($model, 'uidobject', ['template' => '{input}'])->dropdownList(
            $objectOptionArr,
            [
                'class' => 'styler select__default consumption',
                'prompt' => 'Все объекты'

            ]); ?>

    </div>
    <div class="group large">
        <div class="label">Выбрать период:</div>
        <?= $form->field($model, 'withdate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'from_dialog']) ?>
        <?= $form->field($model, 'bydate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'to_dialog']) ?>

        <?= Html::submitButton('Сформировать', ['class' => 'btn submit-btn ploader']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
if (isset($objectsData['Object'])) {
    $i=1;
        if (isset($objectsData['Object']['FullName'])) {
            if (empty($model->uidobject) || $model->uidobject == $objectsData['Object']['UIDObject']) {
                echo $this->render('_consumptionObject', [
                    'object' => $objectsData['Object'],
                    'i' => $i,
                    'tooltip' => $tooltip,
                    'xMonth' => $xMonth,
                    'open' => $model->uidobject == $objectsData['Object']['UIDObject']
                ]);
            }
        } else {
            foreach ($objectsData['Object'] as $arr) {
                if (empty($model->uidobject) || $model->uidobject == $arr['UIDObject']) {
                    echo $this->render('_consumptionObject', [
                        'object' => $arr,
                        'i' => $i,
                        'tooltip' => $tooltip,
                        'xMonth' => $xMonth,
                        'open' => $model->uidobject == $arr['UIDObject']
                    ]);
                }
                $i++;
            }
        }
    if ($i > 10 && empty($model->uidobject)) echo '<div class="wrap-paginate"><a href="#" class="btn more">Показать все</a></div>';
}
//?>
<div class="bts">
    <?= Html::a('Скачать', [
        'main/access-history-file',
        'print' => 'false',
        'action' => 'download_report_consumption',
        'uid' => $model->uid,
        'withdate' => $model->withdate,
        'bydate' => $model->bydate
    ], ['class' => 'btn small  download', 'target' => '_blank']) ?>
    <?= Html::a('Печать', [
        'main/access-history-file',
        'print' => 'true',
        'action' => 'download_report_consumption',
        'uid' => $model->uid,
        'withdate' => $model->withdate,
        'bydate' => $model->bydate
    ], ['class' => 'btn small  print', 'target' => '_blank']) ?>
</div>
