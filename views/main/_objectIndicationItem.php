<?php

/* @var $object */
/* @var $one */

use yii\helpers\Html;

?>

<div class="objects-item wrap-object <?=($one)?'open':''?>" data-id="<?= $object['UIDObject'] ?>"
     data-act-contract="<?= $object['ActContract'] ?>"
     data-act-contractor="<?= $object['ActСontractor'] ?>"
     data-act-date="<?= $object['ActDate'] ?>">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $object['Name'] ?></a></div>
        <!--div class="info">Системное сообщение по объекту (Срок проверки счетчика №___12.12.2020</div-->
    </div>
    <div class="objects-body" style="display: <?=($one)?'block':'none'?>;">
        <?php if (isset($object['Expand']['PU'])) { ?>
            <!-- sub obj items -->
            <div class="sub-objects-items collapse-items">

                <?php
                if (isset($object['Expand']['PU']['FullName'])) {
                    echo $this->render('_puIndicationItem', [
                        'pu' => $object['Expand']['PU'],
                        'onePU' => true
                    ]);
                } else {
                    foreach ($object['Expand']['PU'] as $arr) {
                        if (!empty($arr['FullName'])) {
                            echo $this->render('_puIndicationItem', [
                                'pu' => $arr,
                                'onePU' => false
                            ]);
                        }
                    }
                }


                ?>


            </div>

            <div class="sub-objects-summary border-box">
                <div class="summary-value">
                    <div class="label">Общий объём <br/>по объекту:</div>
                    <div class="value"><strong class="object-result">0</strong> кВтч</div>
                </div>
                <div class="bts">
                    <a href="#" class="btn full transfer-object">Передать показания</a>
                    <!--a href="#" class="btn full border">Сформировать акт фиксации</a-->
                    <?= Html::a('Сформировать акт фиксации', ['ajax/reconciliation', 'uid' => $object['UID']], ['class' => 'btn full border get-act']) ?>

                </div>
            </div>
            <?php
        } else {
            echo '<h2>Приборы учета отсутствуют</h2>';
        }
        ?>
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть объект" data-text-close="Свернуть объект">
            <span><?=($one)?'Свернуть объект':'Развернуть объект'?></span>
        </a>
    </div>
</div>
