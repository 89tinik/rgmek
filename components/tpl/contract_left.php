<?php
/* @var $contract */
/* @var $byDate */
/* @var $fromDate */

use yii\helpers\Html;

//$dateArr = explode('T', $contract['Date']);
$dateArr = explode('-', $dateArr[0]);
if (Yii::$app->request->get('uid') == $contract['UID']) {
    $activeClass = 'active';
} else {
    $activeClass = '';
}
?>
<li>
    <a href="javascript:void(0);"
       class="<?= $activeClass ?>"
       data-name="<?= $contract['FullName'] ?>"
       data-uid="<?=$contract['UID']?>"
       data-odn="<?php echo (in_array($contract['Category'], ['2.3','4.2'])) ? 'true' :  'false';?>">
        <?= $contract['FullName'] ?><span>Какой-то текст никому не известно</span>
    </a>
    <ul>
        <li><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['UID']]) ?></li>
        <li><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['UID']]) ?></li>
        <li><a href="#">Счета</a></li>
        <li><?= Html::a('Начисления  и платежи', ['main/payment', 'uid' => $contract['UID'], 'dateFrom'=>$fromDate, 'dateBy'=>$byDate]) ?></li>
        <li><?= Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid' => $contract['UID']]) ?></li>
    </ul>
    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
        <div class="title">Выбрать действие</div>
        <div class="close">Закрыть</div>
        <div class="bts">
            <div class="btn full small border white"><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['UID']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['UID']]) ?></div>
            <div class="btn full small border white">Счета</div>
            <div class="btn full small border white"><?= Html::a('Начисления  и платежи', ['main/payment', 'uid' => $contract['UID']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid' => $contract['UID']]) ?></div>
        </div>
    </div>
</li>
