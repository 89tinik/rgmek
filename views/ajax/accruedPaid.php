<div class="info-bottom">
    <h3>Отчёт о начислениях и платежах за период с <?=$query['withdate']?> по <?=$query['bydate']?></h3>
    <div class="title">
        <div class="label">Сальдо на начало периода</div>
        <div class="value"><?=$result['DebtStart']?>  руб.</div>
    </div>
    <div class="list">
        <ul>
            <li>
                <span class="name">Начисленно</span>
                <span class="value"><?=$result['AllAccrual']?> руб.</span>
            </li>
            <li>
                <span class="name">Оплачено</span>
                <span class="value"><?=$result['AllPayment']?> руб.</span>
            </li>
        </ul>
    </div>
    <div class="title">
        <div class="label">Сальдо на конец периода</div>
        <div class="value"><?=$result['DebtEnd']?>  руб.</div>
    </div>
</div>



<?php
if (isset($result['Month'])) {
    if (isset($result['Month']['Value'])) {
        echo $this->render('_apMonthItem', [
            'apMonth' => $result['Month']
        ]);
    } else {
        foreach ($result['Month'] as $arr) {
            echo $this->render('_apMonthItem', [
                'apMonth' => $arr
            ]);
        }
    }
}