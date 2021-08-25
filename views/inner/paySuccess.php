<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Успешная оплата | ЛК РГМЭК';
$receiptUID = Yii::$app->session->getFlash('receiptUID');
?>
<div class="page-heading">
    <h1 class="title">Оплата успешно проведена.</h1>
</div>
<div class="h-titles">
    <div class="h-subtitle">
        Информация о платеже отразится в Личном кабинете в течении часа.
    </div>
    <p>Квитанция об оплате <?= Yii::$app->session->getFlash('receiptN1C') ?>

        <?= Html::a('Печать', ['inner/download-receipt', 'uidpaydoc' => $receiptUID, 'print' => 'true'], ['class' => 'btn small border', 'target' => '_blank', 'style' => 'margin-top: -13px;']) ?>
        <?= Html::a('Скачать', ['inner/download-receipt', 'uidpaydoc' => $receiptUID, 'print' => 'false'], ['class' => 'btn small border', 'target' => '_blank', 'style' => 'margin-top: -13px;']) ?>

    </p>
</div>