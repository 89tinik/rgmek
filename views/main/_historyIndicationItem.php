<?php

/* @var $pu */
/* @var $currentTU */
/* @var $model */
/* @var $UIDContract */

use yii\helpers\Html;

?>

<div class="objects-item wrap-object history-wrap <?= ($currentTU == $pu['UIDTU']) ? 'open' : '' ?>">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $pu['FullName'] ?></a></div>
        <div class="date-install"><b>Дата установки: <?= $pu['InstallationDate'] ?></b></div>
        <div class="info"><?= $pu['Purpose'] ?></div>
        <div class="info blue"><?= $pu['KTTName'] ?></div>
    </div>
    
    <style>
        .invoice-table{
        	overflow-x:auto;
        }	
        table.tab-invoice {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }
        table.tab-invoice tr {
        }
        table.tab-invoice th, table.tab-invoice td {
        	text-align: left;
            padding: 8px;
        }
        
        table.tab-invoice th{
        	font-weight: bold;
        }
        .invoice-table table tr td{
            display:table-cell;
        }
        .invoice-table table, .invoice-table table tbody, .invoice-table table tr, .invoice-table table td{
            display:revert;
        }
        .invoice-table table tr td{
            float:none;
            font-size:12px;
            text-align:center;
        }
        .invoice-table table td .price{
            font-size:12px;
        }
        .checkbox-item strong{
            font-size:12px;
        }
        .kvt{
            display:none;
        }
        .checkbox-item span{
            font-size:12px;
        }
        @media screen and (min-width: 1200px){
            .invoice-table table tr td{
             width:33%;   
            }
        }

    </style>
    
    <?php if (isset($pu['Line'])) {?>
    <div class="objects-body" style="display: <?= ($currentTU == $pu['UIDTU']) ? 'block' : 'none' ?>;">
        <div class="invoice-table">
            <table class="tab-invoice">
                <tbody>
                    <tr>
                        <td>Месяц</td>
                        <td>Показание/источник показаний</td>
                        <td>Расход, кВт ч</td>
                    </tr>
                <?php

                    if (isset($pu['Line']['Date'])) {
                        echo $this->render('_historyLine', [
                            'line' => $pu['Line']
                        ]);
                    } else {
                        foreach ($pu['Line'] as $arr) {
                            echo $this->render('_historyLine', [
                                'line' => $arr
                            ]);
                        }
                    }
                ?>
                </tbody>
            </table>
            <div class="bts">
                <?= Html::a('Печать', [
                    'main/access-history-file',
                    'print' => 'true',
                    'action' => 'download_history_ind',
                    'uidtu' => $pu['UIDTU'],
                    'uidobject' => $model->uidobject,
                    'withdate' => $model->withdate,
                    'bydate' => $model->bydate
                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                <?= Html::a('Скачать', [
                    'main/access-history-file',
                    'print' => 'false',
                    'action' => 'download_history_ind',
                    'uidtu' => $pu['UIDTU'],
                    'uidobject' => $model->uidobject,
                    'withdate' => $model->withdate,
                    'bydate' => $model->bydate
                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
            </div>
        </div>
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?= ($currentTU == $pu['UIDTU']) ? 'Свернуть' : 'Развернуть' ?></span>
        </a>
    </div>
    <?php } ?>
</div>
