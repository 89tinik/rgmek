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
        <?= $contract['full_name'] ?><span><?=$contract['status_name']?></span>
    </a>
    <?php if (empty($contract['status'])):?>
    
    <ul>
        <li><span>Техническое присоединение</span></li>
        <li><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Начисления и платежи', ['main/payment', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
    </ul>
    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
        <div class="title">Выбрать действие</div>
        <div class="close">Закрыть</div>
        <div class="bts">
            <div class="btn full small border white"><span>Техническое присоединение</span></div>
            <div class="btn full small border white"><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Начисления и платежи', ['main/payment', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
        </div>
    </div>
    <?php elseif($contract['status'] == 'Заключение'): ?>
        <ul>
            <li><?= Html::a('Техническое присоединение', ['main/tehadd', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
            <li><span>Перейти к оплате</span></li>
            <li><span>Передать показания</span></li>
            <li><span>Счета</span></li>
            <li><span>Начисления и платежи</span></li>
            <li><span>Действующие объекты и приборы учёта</span></li>
        </ul>
        <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
            <div class="title">Выбрать действие</div>
            <div class="close">Закрыть</div>
            <div class="bts">
                <div class="btn full small border white"><?= Html::a('Техническое присоединение', ['main/tehadd', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
                <div class="btn full small border white"><span>Перейти к оплате</span></div>
                <div class="btn full small border white"><span>Передать показания</span></div>
                <div class="btn full small border white"><span>Счета</span></div>
                <div class="btn full small border white"><span>Начисления и платежи</span></div>
                <div class="btn full small border white"><span>Действующие объекты и приборы учёта</span></div>
            </div>
        </div>
    <?php elseif($contract['status'] == 'Соглашение'): ?>
        <ul>
            <li><?= Html::a('Техническое присоединение', ['main/tehadd', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Начисления и платежи', ['main/payment', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        <li><?= Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></li>
        </ul>
        <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
            <div class="title">Выбрать действие</div>
            <div class="close">Закрыть</div>
            <div class="bts">
                <div class="btn full small border white"><?= Html::a('Техническое присоединение', ['main/tehadd', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Перейти к оплате', ['main/arrear', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Передать показания', ['main/indication', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Счета', ['main/invoice', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Начисления и платежи', ['main/payment', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            <div class="btn full small border white"><?= Html::a('Действующие объекты и приборы учёта', ['main/objects', 'uid' => $contract['uid']], ['class' => 'ploader']) ?></div>
            </div>
        </div>
    <?php endif; ?>
</li>
