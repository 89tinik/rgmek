<?php

/* @var $object */

use yii\helpers\Html;

?>

<div class="objects-item wrap-object history-wrap">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $object['FullName'] ?></a></div>
<!--        <div class="date-install"><b>Дата установки: --><?//= $pu['InstallationDate'] ?><!--</b></div>-->
<!--        <div class="info">--><?//= $pu['Purpose'] ?><!--</div>-->
<!--        <div class="info blue">--><?//= $pu['KTTName'] ?><!--</div>-->
    </div>
    <?php if (isset($object['Line'])) {?>
    <div class="objects-body" style="display:none">
        <div class="invoice-table">
            <table>
                <tbody>
                <?php

                    if (isset($object['Line']['Date'])) {
                        echo $this->render('_consumptionMonth', [
                            'line' => $object['Line']
                        ]);
                    } else {
                        foreach ($object['Line'] as $arr) {
                            echo $this->render('_consumptionMonth', [
                                'line' => $arr
                            ]);
                        }
                    }
                ?>
                </tbody>
            </table>
<!--            <div class="bts">-->
<!--                --><?//= Html::a('Печать', [
//                    'main/access-history-file',
//                    'print' => 'true',
//                    'action' => 'download_history_ind',
//                    'uidtu' => $pu['UIDTU'],
//                    'uidobject' => $model->uidobject,
//                    'withdate' => $model->withdate,
//                    'bydate' => $model->bydate
//                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
<!--                --><?//= Html::a('Скачать', [
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
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
<!--            <span>--><?//= ($currentTU == $pu['UIDTU']) ? 'Свернуть' : 'Развернуть' ?><!--</span>-->
            <span>Развернуть</span>
        </a>
    </div>
    <?php } ?>
</div>
