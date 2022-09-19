<?php

/* @var $this yii\web\View */
/* @var $objectsData */

use yii\helpers\Html;

$this->title = 'Технологическое присоединение |  ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Технологическое присоединение</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span><span style="color:red;"> (<?= $this->context->currentContractStatus?>)</span>
    </div>
</div>

<div class="tehpris">
    <p>Порядок заключения договора энергоснабжения до завершения процедуры технологического присоединения описан в
        разделе <a href="https://www.rgmek.ru/business-clients/contracts.html" target="_blank">Как заключить / изменить договор</a></p>
    <p>В личном кабинете размещаются документы по заявкам юридических лиц и индивидуальных предпринимателей поданным
        после 01.07.2022, на технологическое присоединение к электрическим сетям энергопринимающих устройств,
        максимальная мощность которых не превышает 150 кВт, а категория надежности – не выше второй (<a target="_blank" href="http://www.consultant.ru/document/cons_doc_LAW_130498/?ysclid=l702egl2lb849095115">п.39 (3)
            Основных положений функционирования розничных рынков электрической энергии (утв. Постановлением
            Правительства РФ от 04.05.2012 г.№442)</a>)</p>

    <?php
    if (isset($objectsData['Object'])) {
        $i=1;
        $one = true;
        if (isset($objectsData['Object']['Name'])) {
            echo $this->render('_tehaddObject', [
                'object' => $objectsData['Object'],
                'i' => $i,
                'one' => $one
            ]);
        } else {
            foreach ($objectsData['Object'] as $arr) {
                echo $this->render('_tehaddObject', [
                    'object' => $arr,
                    'i' => $i,
                    'one' => $one
                ]);
                $i++;
                $one = false;
            }
        }

    }
    //?>
    <div style="display:none;">
<div id="popap-info-tehadd">Подписанный договор (соглашение) отразится в Личном кабинете в течение 3-х дней.</div>
<div id="popap-info-tehadd-1">Проект договора не содержит существенных условий. Они будут определены после оформления акта об осуществлении технологического присоединения (уведомления). <br>Подпишите документ ЭЦП и направьте по кнопке ниже. Чтобы подписать в бумажном виде обратитесь в офис г. Рязань, ул. Радищева, 61, каб.1</div>
<div id="popap-info-tehadd-2">Акт размещается не позднее 7 рабочих дней с даты оформления сетевой организацией</div>
<div id="popap-info-tehadd-3">Акт (уведомление) размещается не позднее 2 рабочих дней с даты оформления сетевой организацией</div>
<div id="popap-info-tehadd-4">Документ оформлен по итогам выполнения процедуры технологического присоединения <br> Подпишите документ ЭЦП и направьте по кнопке ниже. Чтобы подписать в бумажном виде обратитесь в офис г. Рязань, ул. Радищева, 61, каб.1</div>
</div>
</div>