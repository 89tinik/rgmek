<?php

/* @var $object */
/* @var $i */

/* @var $one */

use yii\helpers\Html;

$chartDataArr = array_fill(1, 12, 0);
$chartYarsDataArr = [];
$seriesArr = [];
?>

<div class="objects-item wrap-object <?= ($one) ? 'open' : '' ?>">
    <div class="objects-head">
        <div class="name"><a href="#"><?= $object['Name'] ?></a></div>
        <?php if (isset($object['Application']['FullName'])) {
            echo '<div class="info">' . $object['Application']['FullName'] . ' - ' . $object['Application']['Status'] .  '</div>';
        } else {
            foreach ($object['Application'] as $app) {
                echo '<div class="info gray">' . $app['FullName'] . ' - ' . $app['Status'] . '</div>';
            }
        } ?>
        <div class="company">Сетевая организация <a href="https://www.rgmek.ru/business-clients/contracts.html#ankor-3" target="_blank"><?=$object['Application']['NetworkOrganization']?></a></div>
    </div>
    <div class="objects-body" style="display: <?= ($one) ? 'block' : 'none' ?>;">
        <div class="sub-objects-items collapse-items">
            <table>
                <tbody>
                <?php
                $i=1;
                if (isset($object['Application']['Document'])) {
                    foreach ($object['Application']['Document'] as $doc) {
                        echo $this->render('_tehaddObjectDoc', [
                            'doc' => $doc,
                            'i' => $i
                        ]);
                        $i++;
                    }
                } else {
                    foreach ($object['Application'] as $app) {
                        foreach ($app['Document'] as $doc) {
                            if ($app['Status'] != 'отозвана') {
                                echo $this->render('_tehaddObjectDoc', [
                                    'doc' => $doc,
                                    'i' => $i
                                ]);
                                $i++;
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>

            <p>Скачайте документ, добавьте свою электронно-цифровую подпись и направьте нам одним из способов:</p>
            
            <div class="leftright">
                <ul>
                    <li>через сервис «<?= Html::a('Написать обращение', ['inner/fos', 'tehadd' => 'true'], ['class' => 'ploader ']) ?>» Личного кабинета;</li>
                    <li>посредством электронного документооборота СБИС, Диадок.</li>
                </ul>
                <div style="clear:both;" class="dnone"></div>
                <?= Html::a('Направить подписанный документ', ['inner/fos', 'tehadd' => 'true'], ['class' => 'ploader docSend']) ?>
                <a href="#popap-info-tehadd" data-fancybox class="popup btn small border papp-tehadd">?</a>
            </div>
            <div style="clear:both;"></div>
            
            <p>Также подписанный договор (соглашение) в бумажном виде можно вернуть по адресу: г. Рязань, ул.
                Радищева, д. 61, каб. 1</p>
            <p>Договор (соглашение) считается заключенным с даты составления акта об осуществлении технологического
                присоединения (уведомления об обеспечении сетевой организацией возможности присоединения к
                электрическим сетям) <a
                        href="https://www.rgmek.ru/business-clients/contracts.html#ankor-1"
                        target="_blank">подробнее</a></p>
        </div>
        
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?= ($one) ? 'Свернуть' : 'Развернуть' ?></span>
        </a>
    </div>
</div>