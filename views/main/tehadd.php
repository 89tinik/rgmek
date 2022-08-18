<?php

/* @var $this yii\web\View */
/* @var $objectsData */

use yii\helpers\Html;

$this->title = 'Технологическое присоединение |  ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Технологическое присоединение</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span><span style="color:red;"> (соглашение к договору на стадии заключения)</span>
    </div>
</div>

<div class="tehpris">
    <p>Порядок заключения договора энергоснабжения до завершения процедуры технологического присоединения описан в
        разделе <a href="https://www.rgmek.ru/business-clients/contracts.html">Как заключить / изменить договор</a></p>
    <p>В личном кабинете размещаются документы по заявкам юридических лиц и индивидуальных предпринимателей поданным
        после 01.07.2022, на технологическое присоединение к электрическим сетям энергопринимающих устройств,
        максимальная мощность которых не превышает 150 кВт, а категория надежности – не выше второй (<a href="">п.39 (3)
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

</div>