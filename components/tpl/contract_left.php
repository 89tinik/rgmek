<?php
$dateArr = explode('T', $contract['Date']);
$dateArr = $dateArr[0];
?>
<li>
    <a href="<?= $contract['UID']?>">№<?= $contract['Number']?> от <?= $dateArr[2].'.'.$dateArr[1].'.'.$dateArr[0]?> <span>Какой-то текст никому не известно</span></a>
    <ul>
        <li><a href="#">Перейти к оплате</a></li>
        <li><a href="#">Передать показания</a></li>
        <li><a href="#">Счета</a></li>
        <li><a href="#">Начисления и платежи</a></li>
        <li><a href="#">Объекты и приборы учета</a></li>
    </ul>
    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
        <div class="title">Выбрать действие</div>
        <div class="close">Закрыть</div>
        <div class="bts">
            <div class="btn full small border white">Перейти к оплате</div>
            <div class="btn full small border white">Передать показания</div>
            <div class="btn full small border white">Счета</div>
            <div class="btn full small border white">Начисления и платежи</div>
            <div class="btn full small border white">Объекты и приборы учета</div>
        </div>
    </div>
</li>
