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

<div class="objects-item wrap-object history-wrap">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $object['FullName'] ?></a></div>
        <div class="btnChart<?=$i?>">Chart</div>
        <!--        <div class="date-install"><b>Дата установки: --><? //= $pu['InstallationDate'] ?><!--</b></div>-->
        <!--        <div class="info">--><? //= $pu['Purpose'] ?><!--</div>-->
        <!--        <div class="info blue">--><? //= $pu['KTTName'] ?><!--</div>-->
    </div>
    <?php if (isset($object['Line'])) { ?>
        <div class="objects-body" style="display:none">
            <div class="invoice-table">
                <table>
                    <tbody>
                    <?php

                    if (isset($object['Line']['Date'])) {

                        $dateArr = explode('.', $object['Line']['Date']);
                        $chartDataArr[intval($dateArr['1'])] = $object['Line']['Volume'];
                        $seriesArr[] = "{name: '" . $object['Line']['Year'] . "', data: [" . implode(',', $chartDataArr) . "]}";

                        echo $this->render('_consumptionMonth', [
                            'line' => $object['Line']
                        ]);
                    } else {
                        $currentYear = $object['Line'][0]['Year'];
                        foreach ($object['Line'] as $arr) {

                            if ($currentYear != $arr['Year']) {
                                $seriesArr[] = "{name: '" . $currentYear . "', data: [" . implode(',', $chartDataArr) . "]}";
                                $chartDataArr = array_fill(1, 12, 0);
                                $currentYear = $arr['Year'];
                            }
                            $dateArr = explode('.', $arr['Date']);
                            $chartDataArr[intval($dateArr['1'])] = $arr['Volume'];

                            echo $this->render('_consumptionMonth', [
                                'line' => $arr
                            ]);
                        }
                        $seriesArr[] = "{name: '" . $currentYear . "', data: [" . implode(',', $chartDataArr) . "]}";
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
            $js = "$('.btnChart$i').on('click', function(){
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
        </div>
    <?php } ?>
</div>
