<?php

/* @var $object */
/* @var $i */
/* @var $one*/

use yii\helpers\Html;

$chartDataArr = array_fill(1, 12, 0);
$chartYarsDataArr = [];
$seriesArr = [];
?>

<div class="objects-item wrap-object <?= ($one) ? 'open' : '' ?>">
    <div class="objects-head">
        <div class="name"><a href="#"><?=$object['Name']?></a></div>
        <div class="info"><?=$object['Application']['FullName']?></div>
    </div>
    <div class="objects-body" style="display: <?= ($one) ? 'block' : 'none' ?>;">
        <div class="sub-objects-items collapse-items">
            <table>
                <tbody>
                <tr>
                    <td>01.07.2022</td>
                    <td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности)
                        / Соглашение
                    </td>
                    <td>icon</td>
                    <td>
                        <?= Html::a('Скачать', [
                            'main/access-tehadd-file',
                            'print' => 'true',
                            'action' => 'download_report_consumption',
                            'uid' => \Yii::$app->request->get('uid'),
                            'path' => $object['Application']['Document']['Path'],
                            'name' => $object['Application']['Document']['Name']
                        ], ['class' => 'btn full', 'target' => '_blank']) ?>
                    </td>
                </tr>
                <tr>
                    <td>01.07.2022</td>
                    <td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности)
                        / Соглашение
                    </td>
                    <td>icon</td>
                    <td>
                        <button class="btn full">Скачать</button>
                    </td>
                </tr>
                <tr>
                    <td>01.07.2022</td>
                    <td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности)
                        / Соглашение
                    </td>
                    <td>icon</td>
                    <td>
                        <button class="btn full">Скачать</button>
                    </td>
                </tr>
                <tr>
                    <td>01.07.2022</td>
                    <td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности)
                        / Соглашение
                    </td>
                    <td>icon</td>
                    <td>
                        <button class="btn full">Скачать</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <p>Скачайте документ, подпишите своей электронно-цифровой подписью, направьте нам одним из способов:</p>
            <ul>
                <li>через сервис «<?= Html::a('Написать обращение', ['inner/fos']) ?>» Личного кабинета;
                </li>
                <li>посредством электронного документооборота СБИС, Диадок.</li>
            </ul>
            <p>Также подписанный договор (соглашение) в бумажном виде можно вернуть по адресу: г. Рязань, ул.
                Радищева, д. 61, каб. 1</p>
            <p>Договор (соглашение) считается заключенным с даты составления акта об осуществлении технологического
                присоединения (уведомления об обеспечении сетевой организацией возможности присоединения к
                электрическим сетям) <a href="#">подробнее</a></p>
        </div>
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?= ($one) ? 'Свернуть' : 'Развернуть' ?></span>
        </a>
    </div>
</div>