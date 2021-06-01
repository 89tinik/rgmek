<?php
/* @var $this yii\web\View */
/* @var $result */

$this->title = 'Узнать задолжность, оплатить | ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Узнать задолжность, оплатить</strong><span class="sep"></span>
        <span>	Договор  <?= $result['Contract']['FullName'] ?></span>
    </div>
</div>

<div class="arrear-right">
    <div class="payment-form white-box">
        <div class="title">Оплата</div>
        <div class="group">
            <div class="field">
                <div class="label">Электроэнергия:</div>
                <div class="value">
                    <input type="text" placeholder="Введите" value="<?= (!empty($result['Contract']['Expand']['ElectricityDebt'])) ? str_replace(' ', '', $result['Contract']['Expand']['ElectricityDebt']) : 0; ?>"/>
                </div>
            </div>
        </div>
        <div class="group">
            <div class="field">
                <div class="label">Пени:</div>
                <div class="value ruble">
                    <input type="text"
                           value="<?= (!empty($result['Contract']['Expand']['CurrentPenalty'])) ? str_replace(' ', '', $result['Contract']['Expand']['CurrentPenalty']) : 0; ?>"/>
                </div>
            </div>
        </div>
        <div class="group-price">
            <div class="label">Итого:</div>
            <div class="price"><?= (!empty($result['Contract']['TotalDebt'])) ? $result['Contract']['TotalDebt'] . ' ₽' : 0; ?></div>
        </div>
        <input type="submit" class="btn submit-btn" value="Перейти к оплате"/>
    </div>
</div>

<div class="arrear-left">
    <div class="arrear-summary border-box">
        <div class="info-top">
            <div class="title">
                <!--Какой-то текст ни кому не известно-->
                <strong><?= $result['Contract']['FullName'] ?></strong>
            </div>
        </div>
        <div class="info-bottom">
            <div class="title">
                <div class="label">Долг на <?= date('d.m.Y') ?> электроэнергия</div>
                <div class="value"><?= (!empty($result['Contract']['Expand']['CurrentDebt'])) ? $result['Contract']['Expand']['CurrentDebt'] . ' руб.' : 0; ?></div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Электроэнергия</span>
                        <span class="value"><?= (!empty($result['Contract']['Expand']['ElectricityDebt'])) ? $result['Contract']['Expand']['ElectricityDebt'] . ' руб.' : 0; ?></span>
                    </li>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($result['Contract']['Expand']['CurrentPenalty'])) ? $result['Contract']['Expand']['CurrentPenalty'] . ' руб.' : 0; ?></span>
                    </li>
                </ul>
            </div>
            <div class="title">
                <div class="label">Предстоящие платежи текущего месяца</div>
                <div class="value"><?= (!empty($result['Contract']['Expand']['UpcomingDebt'])) ? $result['Contract']['Expand']['UpcomingDebt'] . ' руб.' : 0; ?></div>
            </div>
            <!--div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($result['Contract']['UpcomingPenalty'])) ? $result['Contract']['UpcomingPenalty'] . ' руб.' : 0; ?></span>
                    </li>
                </ul>
            </div-->
            <?php if (!empty($result['Expand']['Overpayment'])):?>
                <div class="title">
                    <div class="label">Преплата</div>
                    <div class="value"><?= $result['Expand']['Overpayment'].' руб.'?></div>
                </div>
            <?php endif;?>
            <div class="itog">
                Итого:
                <div class="value"><?= (!empty($result['Contract']['TotalDebt'])) ? $result['Contract']['TotalDebt'] . ' ₽' : 0; ?> </div>
            </div>
        </div>
        <?php if (!empty($result['Contract']['DateDisable'])): ?>
            <div class="info-notice">Вы включены в график отключений на <?= $result['Contract']['DateDisable'] ?></div>
        <?php endif; ?>

    </div>
    <?php  if (isset($result['Account'])) : ?>
    <div class="arrear-lists white-box">
        <div class="white-box-title">Счета текущего расчетного периода</div>
        <div class="list">
            <ul class="wrap-invoice">
                <?php
                if (isset($result['Account']['FullName'])) {
                    echo $this->render('_invoiceItem', [
                        'invoice' => $result['Account']
                    ]);
                } else {
                    foreach ($result['Account'] as $arr) {
                        echo $this->render('_invoiceItem', [
                            'invoice' => $arr
                        ]);
                    }
                }
                ?>


            </ul>
        </div>
    </div>
    <?php endif; ?>
    <div class="bts">
        <a href="#" class="btn border full aj-all-invoice" data-uid="<?= $result['Contract']['UID'] ?>">Все выставленные
            счета</a>
    </div>
</div>