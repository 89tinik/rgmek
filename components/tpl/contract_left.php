<?php
/* @var $contract */

use yii\helpers\Html;

//$dateArr = explode('T', $contract['Date']);
$dateArr = explode('-',$dateArr[0]);
?>
<li>
    <a href="javascript:void(0);"><?=$contract['FullName']?><span>Какой-то текст никому не известно</span></a>
    <ul>
        <li><?=Html::a('Перейти к оплате', ['main/arrear', 'uid'=>$contract['UID']])?></li>
        <li><a href="#">Передать показания</a></li>
        <li><a href="#">Счета</a></li>
        <li><a href="#">Начисления и платежи</a></li>
        <li><?=Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid'=>$contract['UID']])?></li>
    </ul>
    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
        <div class="title">Выбрать действие</div>
        <div class="close">Закрыть</div>
        <div class="bts">
            <div class="btn full small border white"><?=Html::a('Перейти к оплате', ['main/arrear', 'uid'=>$contract['UID']])?></div>
            <div class="btn full small border white">Передать показания</div>
            <div class="btn full small border white">Счета</div>
            <div class="btn full small border white">Начисления и платежи</div>
            <div class="btn full small border white"><?=Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid'=>$contract['UID']])?></div>
        </div>
    </div>
</li>
