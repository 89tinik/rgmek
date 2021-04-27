<?php

/* @var $contract */

use yii\helpers\Html;
?>

<div class="summary-item <?php if (!empty($contract['DateDisable'])):?>notice<?php endif;?>" data-uid="<?=$contract['UID']?>">
    <div class="info">
        <div class="info-top">
            <div class="name">
                Какой-то текст никому не известно
                <span class="value"><?=$contract['FullName']?></span>
            </div>
            <div class="price">
                К оплате
                <span class="value"><?=$contract['TotalDebt']?> руб.</span>
            </div>
        </div>

        <div class="info-bottom" style="display: none;">
            <div class="title">
                <div class="label">Долг на <?= date('d.m.Y')?>  электроноэнергия</div>
                <div class="value"><?= (!empty($contract['CurrentDebt'])) ? $contract['CurrentDebt'].' руб.' : 0 ;?>  </div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($contract['CurrentPenalty'])) ? $contract['CurrentPenalty'].' руб.' : 0 ;?></span>
                    </li>
                </ul>
            </div>
            <div class="title">
                <div class="label">Предстоящие платежи текущего месяца</div>
                <div class="value"><?= (!empty($contract['UpcomingDebt'])) ? $contract['UpcomingDebt'].' руб.' : 0 ;?></div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($contract['UpcomingPenalty'])) ? $contract['UpcomingPenalty'].' руб.' : 0 ;?></span>
                    </li>
                </ul>
            </div>
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
            <li class="item-3"><a href="#">Счета</a></li>
            <li class="item-4"><a href="#">Начисления  и платежи</a></li>
            <li class="item-5"><?=Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid'=>$contract['UID']])?></li>
        </ul>
    </div>
</div>
