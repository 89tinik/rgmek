<?php

/* @var $contract */
/* @var $fromDate */
/* @var $byDate */

use yii\helpers\Html;
?>

<div class="summary-item <?php if (!empty($contract['DateDisable'])):?>notice<?php endif;?>" data-uid="<?=$contract['UID']?>">
    <div class="info">
        <div class="info-top">
            <div class="name">
                Договор
                <span class="value"><?=$contract['FullName']?></span>
                <span class="status"><?php if(!empty($contract['StatusName'])) echo $contract['StatusName']; ?></span>
            </div>
            <div class="price">
                К оплате
                <span class="value"><?=$contract['TotalDebt']?> руб.</span>
            </div>
        </div>

        <div class="info-bottom" style="display: none;">
            <div class="title">
                <div class="label">Долг на <?= date('d.m.Y')?></div>
                <div class="value"><?= (!empty($contract['Expand']['CurrentDebt'])) ? $contract['Expand']['CurrentDebt'].' руб.' : 0 ;?>  </div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Электроэнергия</span>
                        <span class="value"><?= (!empty($contract['Expand']['ElectricityDebt'])) ? $contract['Expand']['ElectricityDebt'].' руб.' : 0 ;?></span>
                    </li>
                    <?php if(!empty($contract['Expand']['CurrentPenalty'])):?>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= $contract['Expand']['CurrentPenalty'].' руб.';?></span>
                    </li>
                    <?php endif;?>
                    <?php if(!empty($contract['Expand']['Duty'])):?>
                    <li>
                        <span class="name">Госпошлина</span>
                        <span class="value"><?= $contract['Expand']['Duty'].' руб.';?></span>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
            <div class="title">
                <div class="label">Предстоящие платежи</div>
                <div class="value"><?= (!empty($contract['Expand']['UpcomingDebt'])) ? $contract['Expand']['UpcomingDebt'].' руб.' : 0 ;?></div>
            </div>
            <!--div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($contract['Expand']['UpcomingPenalty'])) ? $contract['Expand']['UpcomingPenalty'].' руб.' : 0 ;?></span>
                    </li>
                </ul>
            </div-->

            <?php if (!empty($contract['Expand']['Overpayment'])):?>
                <div class="title">
                    <div class="label">Переплата</div>
                    <div class="value"><?= $contract['Expand']['Overpayment'].' руб.'?></div>
                </div>
            <?php endif;?>
        </div>

        <a href="#" class="show-btn">Подробная детализация</a>
        <?php if (!empty($contract['DateDisable'])):?>
            <div class="notice">Вы включены в график отключений на <?=$contract['DateDisable']?></div>
        <?php endif;?>
    </div>
    <div class="controls">
        <ul>
            <?php if (empty($contract['Status'])):?>
                <li class="item-0"><span>Техническое присоединение</span></li>
                <li class="item-1"><?=Html::a('Перейти к оплате', ['main/arrear', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
                <li class="item-2"><?=Html::a('Передать показания', ['main/indication', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
                <li class="item-3"><?=Html::a('Счета', ['main/invoice', 'uid' => $contract['UID']], ['class' => 'ploader']) ?></li>
                <li class="item-5"><?=Html::a('Начисления и платежи', ['main/payment', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
                <li class="item-5"><?=Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
                <style>
                    .item-6 span{border:none !important;}
                </style>
                <li class="item-6"><?=Html::a('', ['main/consumption', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
            <?php else: ?>
                <li class="item-0"><?=Html::a('Техническое присоединение', ['main/tehadd', 'uid'=>$contract['UID']], ['class' => 'ploader'])?></li>
                <li class="item-1"><span>Перейти к оплате</span></li>
                <li class="item-2"><span>Передать показания</span></li>
                <li class="item-3"><span>Счета</span></li>
                <li class="item-5"><span>Начисления и платежи</span></li>
                <li class="item-5"><span>Действующие объекты и приборы учёта</span></li>
                <style>
                    .item-6 span{border:none !important;}
                </style>
                <li class="item-6"><span></span></li>
           <?php endif; ?>

        </ul>
    </div>
</div>
