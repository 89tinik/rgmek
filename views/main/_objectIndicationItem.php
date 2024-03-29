<?php

/* @var $object */
/* @var $one */
/* @var $model */
/* @var $UIDContract */

use yii\helpers\Html;

?>

<div class="objects-item wrap-object <?=($one)?'open':''?>" data-id="<?= $object['UIDObject'] ?>"
     data-act-contract="<?= $object['ActContract'] ?>"
     data-act-contractor="<?= str_replace('"', '\'', $object['ActСontractor']) ?>"
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
                        'onePU' => true,
                        'UIDContract'=>$UIDContract,
                        'UIDObject'=>$object['UIDObject'],
                        'model'=>$model
                    ]);
                } else {
                    foreach ($object['Expand']['PU'] as $arr) {
                        if (!empty($arr['FullName'])) {
                            echo $this->render('_puIndicationItem', [
                                'pu' => $arr,
                                'onePU' => false,
                                'UIDContract'=>$UIDContract,
                                'UIDObject'=>$object['UIDObject'],
                                'model'=> $model
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
                        <div class="label-error" style="display: none">
                            Вы не передали показания по счётчикам:
                            <div class="empty-pu"></div>
                            <a href="#" class="btn tranfer-empty">Продолжить</a>
                            <br>
                            <a href="#" class="back-empty">Вернуться и внести</a>
                        </div>
                    <a href="#" class="btn full transfer-object <?php if (date('j') > 1 && date('j') < 19) :?>disabled<?php endif;?>">Передать показания</a>
                    <!--a href="#" class="btn full border">Сформировать акт фиксации</a-->
                    <?= Html::a('Сформировать акт фиксации', ['ajax/reconciliation', 'uid' => $object['UID']], ['class' => 'btn full border get-act', 'target'=>'_blank']) ?>

                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="objects-more">
        <?php if (isset($object['Expand']['PU'])) { ?>
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?=($one)?'Свернуть':'Развернуть'?></span>
        </a>
            <?php
        } else {
            echo '<h2>Приборы учета отсутствуют</h2>';
        }
        ?>
    </div>
</div>
