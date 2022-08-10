<?php

/* @var $object */
/* @var $i */
/* @var $tooltip */

/* @var $xMonth */

use yii\helpers\Html;

$chartDataArr = array_fill(1, 12, 0);
$chartYarsDataArr = [];
$seriesArr = [];
?>

<div class="objects-item wrap-object history-wrap consumption">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $object['FullName'] ?></a></div>
        <div class="btnChart<?= $i ?> wrap-checkbox-chart first-show">
            График
            <label class="checkbox-ios">
                <input type="checkbox" checked>
                <span class="checkbox-ios-switch"></span>
            </label>
            Таблица
        </div>
        <!--        <div class="date-install"><b>Дата установки: --><? //= $pu['InstallationDate'] ?><!--</b></div>-->
        <!--        <div class="info">--><? //= $pu['Purpose'] ?><!--</div>-->
        <!--        <div class="info blue">--><? //= $pu['KTTName'] ?><!--</div>-->
    </div>
    
    
    <?php if (isset($object['Line'])) { ?>
        <div class="objects-body" style="display:none">
            <div class="invoice-table consumption">
                <table>
                    <tbody>
                         <tr>
                            <th>Месяц</th>
                            <th>Потребление, кВт ч</th>
                            <th>Способ расчёта</th>
                        </tr>
                    <?php

                    if (isset($object['Line']['Date'])) {

                        $dateArr = explode('.', $object['Line']['Date']);
                        $chartDataArr[intval($dateArr['1'])] = $object['Line']['Volume'];
                        $seriesArr[] = "{name: '" . $object['Line']['Year'] . "', data: [" . implode(',', $chartDataArr) . "]}";

                        echo $this->render('_consumptionMonth', [
                            'line' => [
                                    'Month'=>$object['Line']['Month'],
                                    'Year'=>[$object['Line']['Year']],
                                    'Volume'=>[$object['Line']['Volume']],
                                    'CalculationMethod'=>$object['Line']['CalculationMethod'],
                                    'CalculationMethodName'=>$object['Line']['CalculationMethodName'],
                                    'CalculationMethodInitialData'=>$object['Line']['CalculationMethodInitialData'],
                                ]
                        ]);
                    } else {
                        $currentYear = $object['Line'][0]['Year'];
                        $tableObjectDataArr = array_fill(1, 12, ['Month'=>'', 'Year'=> [], 'Volume'=>[]]);
                        foreach ($object['Line'] as $arr) {
                            if ($currentYear != $arr['Year']) {
                                $seriesArr[] = "{name: '" . $currentYear . "', data: [" . implode(',', $chartDataArr) . "]}";
                                $chartDataArr = array_fill(1, 12, 0);
                                $currentYear = $arr['Year'];
                            }
                            $dateArr = explode('.', $arr['Date']);
                            $chartDataArr[intval($dateArr['1'])] = $arr['Volume'];
                            $tableObjectDataArr[intval($dateArr['1'])]['Month']= $arr['Month'];
                            $tableObjectDataArr[intval($dateArr['1'])]['Year'][]= $arr['Year'];
                            $tableObjectDataArr[intval($dateArr['1'])]['Volume'][]= $arr['Volume'];
                            $tableObjectDataArr[intval($dateArr['1'])]['CalculationMethod']= $arr['CalculationMethod'];
                            $tableObjectDataArr[intval($dateArr['1'])]['CalculationMethodName']= $arr['CalculationMethodName'];
                            $tableObjectDataArr[intval($dateArr['1'])]['CalculationMethodInitialData']= $arr['CalculationMethodInitialData'];
                        }
                        $seriesArr[] = "{name: '" . $currentYear . "', data: [" . implode(',', $chartDataArr) . "]}";
                    }
                    if (isset($tableObjectDataArr)){
                        foreach($tableObjectDataArr as $month){
                            echo $this->render('_consumptionMonth', [
                                'line' => $month
                            ]);
                        }
                    }

                    ?>
                    </tbody>
                </table>
                <!--            <div class="bts">-->
                <!--                --><? //= Html::a('Печать', [
                //                    'main/access-history-file',
                //                    'print' => 'true',
                //                    'action' => 'download_history_ind',
                //                    'uidtu' => $pu['UIDTU'],
                //                    'uidobject' => $model->uidobject,
                //                    'withdate' => $model->withdate,
                //                    'bydate' => $model->bydate
                //                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                <!--                --><? //= Html::a('Скачать', [
                //                    'main/access-history-file',
                //                    'print' => 'false',
                //                    'action' => 'download_history_ind',
                //                    'uidtu' => $pu['UIDTU'],
                //                    'uidobject' => $model->uidobject,
                //                    'withdate' => $model->withdate,
                //                    'bydate' => $model->bydate
                //                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
                <!--            </div>-->
            </div>


            <?php

            $series = implode(',', $seriesArr);
            $js = "$('.btnChart$i input').on('change', function () {
                var containerWrap = $(this).closest('.objects-head').siblings('.objects-body');
                if ($(this).prop('checked')) {
                    containerWrap.children('.invoice-table').show(1000);
                    containerWrap.children('.invoice-chart').hide(1000);
                } else {
                    if ($(this).closest('.wrap-checkbox-chart').hasClass('first-show')) {
                        Highcharts.chart('container$i', {
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
                        });
            
                        $(this).closest('.wrap-checkbox-chart').removeClass('first-show');
                    }
                    containerWrap.children('.invoice-table').hide(1000);
                    containerWrap.children('.invoice-chart').show(1000);
                }
            
            });
        ";
            $this->registerJs($js);

            ?>
            <div class="invoice-chart">
                <figure class="highcharts-figure">
                    <div id="container<?= $i ?>"></div>
                </figure>
            </div>
        </div>


        <div class="objects-more">
            <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
                <!--            <span>-->
                <? //= ($currentTU == $pu['UIDTU']) ? 'Свернуть' : 'Развернуть' ?><!--</span>-->
                <span>Развернуть</span>
            </a>
            <?php if ($object['ISU'] == 'Да' && $object['StatusObject'] == 'Действующий'): ?>
                <a href="http://93.92.80.25:5001/" class="btn small right" target="_blank">Переход в интелектуальные
                    системы учёта</a>
                <?= Html::a('История показаний', ['main/history', 'uidtu' => 'тутнепонятно', 'uidobject' => $object['UIDObject'], 'uid' => \Yii::$app->request->get('uid')], ['class' => 'small btn right ploader', 'data-uidcontract' => $UIDContract]) ?>

            <?php endif; ?>
        </div>
    <?php } ?>
</div>
