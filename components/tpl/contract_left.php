<?php
/* @var $contract */

use yii\helpers\Html;


if (Yii::$app->request->get('uid') == $contract['uid']) {
    $activeClass = 'active';
} else {
    $activeClass = '';
}
?>
<li>
    <a href="javascript:void(0);"
       class="<?= $activeClass ?>"
       data-name="<?= $contract['full_name'] ?>"
       data-uid="<?=$contract['uid']?>"
       data-odn="<?php echo (in_array($contract['category'], ['2.3','4.2'])) ? 'true' :  'false';?>">
        <?= $contract['full_name'] ?><!--span>Какой-то текст никому не известно</span-->
    </a>
    <ul>
        <li><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']]) ?></li>
        <li><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']]) ?></li>
        <li><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']]) ?></li>
        <li><?= Html::a('Начисления  и платежи', ['main/payment', 'uid' => $contract['uid']]) ?></li>
        <li><?= Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid' => $contract['uid']]) ?></li>
    </ul>
    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
        <div class="title">Выбрать действие</div>
        <div class="close">Закрыть</div>
        <div class="bts">
            <div class="btn full small border white"><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Начисления  и платежи', ['main/payment', 'uid' => $contract['uid']]) ?></div>
            <div class="btn full small border white"><?= Html::a('Действующие объекты  и приборы учёта', ['main/objects', 'uid' => $contract['uid']]) ?></div>
        </div>
    </div>
</li>
