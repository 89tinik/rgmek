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
                <!--Какой-то текст никому не известно-->
                <span class="value"><?=$contract['FullName']?></span>
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
                <div class="label">Предстоящие платежи текущего месяца</div>
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
                    <div class="label">Преплата</div>
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
            <li class="item-1"><?=Html::a('Перейти к оплате', ['main/arrear', 'uid'=>$contract['UID']])?></li>
            <li class="item-2"><?=Html::a('Передать показания', ['main/indication', 'uid'=>$contract['UID']])?></li>
            <li class="item-3"><?=Html::a('Счета', ['main/invoice', 'uid' => $contract['UID'], 'dateFrom'=>$fromDate, 'dateBy'=>$byDate]) ?></li>
            <li class="item-5"><?=Html::a('Начисления и платежи', ['main/payment', 'uid'=>$contract['UID'], 'dateFrom'=>$fromDate, 'dateBy'=>$byDate])?></li>
            <li class="item-5"><?=Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid'=>$contract['UID']])?></li>
        </ul>
    </div>
</div>
