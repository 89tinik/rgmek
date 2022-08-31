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
            echo '<div class="info">' . $object['Application']['FullName'] . '</div>';
        } else {
            foreach ($object['Application'] as $app) {
                if ($app['Status'] == 'отозвана') {
                    echo '<div class="info gray">' . $app['FullName'] . '(' . $app['Status'] . ')</div>';
                } else {
                    echo '<div class="info">' . $app['FullName'] . '</div>';
                }
            }
        } ?>
    </div>
    <div class="objects-body" style="display: <?= ($one) ? 'block' : 'none' ?>;">
        <div class="sub-objects-items collapse-items">
            <table>
                <tbody>
                <?php
                if (isset($object['Application']['Document'])) {
                    foreach ($object['Application']['Document'] as $doc) {
                        echo $this->render('_tehaddObjectDoc', [
                            'doc' => $doc
                        ]);
                    }
                } else {
                    foreach ($object['Application'] as $app) {
                        foreach ($app['Document'] as $doc) {
                            if ($app['Status'] != 'отозвана') {
                                echo $this->render('_tehaddObjectDoc', [
                                    'doc' => $doc
                                ]);
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>

            <p>Скачайте документ, подпишите своей электронно-цифровой подписью, направьте нам одним из способов:</p>
            <style>
                .leftright{
                    height:80px;
                }
                .leftright ul{
                    width:50%;
                    float:left;
                    margin-top:0;
                }
                .leftright .docSend{
                    display:block;
                    padding:10px 15px;
                    background:#4475F2;
                    color:#fff;
                    width:44%;
                    float:left;
                    text-align:center;
                    border-radius:6px;
                }
                .leftright .popup{
                    float:right;
                }
                .leftright .popup img{
                    height:40px;
                }
                @media screen and (max-width: 1200px){
                    .tehpris table tbody tr td .btn{
                        margin-top:0;
                    }
                    .tehpris table tbody tr td.name{
                        width:100%;
                    }
                    .tehpris table tbody tr td.popup{
                        float:right;
                        padding-top:20px;
                    }
                    .tehpris table tbody tr td img{
                        height:25px;
                    }
                    .leftright{
                        height:130px;
                    }
                    .leftright .popup img{
                        height:25px;
                    }
                    .leftright .docSend{
                        width:40%;
                    }
                    .leftright .popup{
                        padding-top:15px;
                    }
                }
            </style>
            <div class="leftright">
                <ul>
                    <li>через сервис «<?= Html::a('Написать обращение', ['inner/fos']) ?>» Личного кабинета;</li>
                    <li>посредством электронного документооборота СБИС, Диадок.</li>
                </ul>
                <a href="#" class="docSend">Направить подписанный документ</a>
                <a href="#popap-info-tehadd" data-fancybox class="popup"><img src="/images/icon.svg" width="50"/></a>
            </div>
            <p>Также подписанный договор (соглашение) в бумажном виде можно вернуть по адресу: г. Рязань, ул.
                Радищева, д. 61, каб. 1</p>
            <p>Договор (соглашение) считается заключенным с даты составления акта об осуществлении технологического
                присоединения (уведомления об обеспечении сетевой организацией возможности присоединения к
                электрическим сетям) <a
                        href="https://www.rgmek.ru/business-clients/contracts.html"
                        target="_blank">подробнее</a></p>
        </div>
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?= ($one) ? 'Свернуть' : 'Развернуть' ?></span>
        </a>
    </div>
</div>