<?php
/* @var $this yii\web\View */
/* @var $result */
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
                    <input type="text" placeholder="Введите"/>
                </div>
            </div>
        </div>
        <div class="group">
            <div class="field">
                <div class="label">Пени:</div>
                <div class="value ruble">
                    <input type="text"
                           value="<?= (!empty($result['Contract']['TotalDebt'])) ? str_replace(' ', '', $result['Contract']['TotalDebt']) : 0; ?>"/>
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
                Какой-то текст ни кому не известно
                <strong><?= $result['Contract']['FullName'] ?></strong>
            </div>
        </div>
        <div class="info-bottom">
            <div class="title">
                <div class="label">Долг на <?= date('d.m.Y') ?> электроноэнергия</div>
                <div class="value"><?= (!empty($result['Contract']['CurrentDebt'])) ? $result['Contract']['CurrentDebt'] . ' руб.' : 0; ?></div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($result['Contract']['CurrentPenalty'])) ? $result['Contract']['CurrentPenalty'] . ' руб.' : 0; ?></span>
                    </li>
                </ul>
            </div>
            <div class="title">
                <div class="label">Предстоящие платежи текущего месяца</div>
                <div class="value"><?= (!empty($result['Contract']['UpcomingDebt'])) ? $result['Contract']['UpcomingDebt'] . ' руб.' : 0; ?></div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($result['Contract']['UpcomingPenalty'])) ? $result['Contract']['UpcomingPenalty'] . ' руб.' : 0; ?></span>
                    </li>
                </ul>
            </div>
            <div class="itog">
                Итого:
                <div class="value"><?= (!empty($result['Contract']['TotalDebt'])) ? $result['Contract']['TotalDebt'] . ' ₽' : 0; ?> </div>
            </div>
        </div>
        <?php if (!empty($result['Contract']['DateDisable'])): ?>
            <div class="info-notice">Вы включены в график отключений на <?= $result['Contract']['DateDisable'] ?></div>
        <?php endif; ?>

    </div>
    <div class="arrear-lists white-box">
        <div class="white-box-title">Счета текущего расчетного периода</div>
        <div class="list">
            <ul class="wrap-invoice">
                <?php
                if (isset($result['Account']['FullName'])) {
                    echo $this->render('_invoiceItem', [
                        'invoice' => $result['Contract']
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
    <div class="bts">
        <a href="#" class="btn border full aj-all-invoice" data-uid="<?= $result['Contract']['UID'] ?>">Все выставленные
            счета</a>
    </div>
</div>