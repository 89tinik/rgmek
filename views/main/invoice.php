<?php

/* @var $this yii\web\View */

/* @var $result */

use yii\helpers\Html;

$this->title = 'Счета |  ЛК РГМЭК';
$contractFullName = Yii::$app->params['contractFullName'];
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Счета</strong><span class="sep"></span>
        <span>Договор <span class="name-sidebar"></span></span>
    </div>
</div>

<div class="payment-filter white-box">
    <form class="get-order-form">
        <div class="group small">
            <div class="field">
                <div class="label">Выбрать тип отчета:</div>
                <div class="value">
                    <select class="styler select__default type-order" required="required">
                        <option></option>
                        <option value="detail">Отчет детализация</option>
                        <option value="invoices">Счета</option>
                        <option value="odn">Отчет По Расчету ОДН</option>
                        <option value="aktpp">Акт приема передачи</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="group large">
            <div class="label">Выбрать период:</div>
            <div class="field">
                <div class="value">
                    <span>с</span>
                    <input type="text" value="<?= \Yii::$app->request->get('dateFrom'); ?>" id="from_dialog"
                           required="required"/>
                </div>
            </div>
            <div class="field">
                <div class="value">
                    <span>По</span>
                    <input type="text" value="<?= \Yii::$app->request->get('dateBy'); ?>" id="to_dialog"
                           required="required"/>
                </div>
            </div>
            <input type="submit" class="btn submit-btn get-report" value="Сформировать"/>
        </div>
    </form>
</div>

<div class="payment-items">
    <div class="payment-item">

        <div class="pack-report-wrap report-item white-box">
            <div class="white-box-title">Пакет расчётных документов за
                <span><?= \Yii::$app->request->get('dateFrom'); ?>-<?= \Yii::$app->request->get('dateBy'); ?></span>
                период
            </div>
            <div class="invoice-table">
                <table>

                    <?php
                    if (isset($result['Account'])) {
                        if (isset($result['Account']['FullName'])) {
                            echo $this->render('_packItem', [
                                'pack' => $result['Account'],
                                'action' => 'download_account'
                            ]);
                        } else {
                            foreach ($result['Account'] as $arr) {
                                echo $this->render('_packItem', [
                                    'pack' => $arr,
                                    'action' => 'download_account'
                                ]);
                            }
                        }
                    }
                    if (isset($result['Act'])) {
                        if (isset($result['Act']['FullName'])) {
                            echo $this->render('_packItem', [
                                'pack' => $result['Act'],
                                'action' => 'download_act_reception_transfer'
                            ]);
                        } else {
                            foreach ($result['Act'] as $arr) {
                                echo $this->render('_packItem', [
                                    'pack' => $arr,
                                    'action' => 'download_act_reception_transfer'
                                ]);
                            }
                        }
                    }
                    ?>


                </table>
            </div>

        </div>


        <div class="arrear-lists white-box invoices-report-wrap report-item big-name" style="display: none;">
            <div class="white-box-title">Счета</div>
            <div class="list">
                <ul>

                </ul>
            </div>
        </div>


        <div class="payment-info border-box detail-report-wrap report-item">
            <div class="title">Детализация счета по договору<br/><span class="name-sidebar"></span><br/>за
                период <?= \Yii::$app->request->get('dateFrom'); ?>-<?= \Yii::$app->request->get('dateBy'); ?></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?= Html::a('Печать', [
                    'main/access-file',
                    'print' => 'true',
                    'action' => 'download_report_detal',
                    'uid' => \Yii::$app->request->get('uid'),
                    'withdate' => \Yii::$app->request->get('dateFrom'),
                    'bydate' => \Yii::$app->request->get('dateBy')
                ], ['class' => 'btn small right print']) ?>
                <?= Html::a('Скачать', [
                    'main/access-file',
                    'print' => 'false',
                    'action' => 'download_report_detal',
                    'uid' => \Yii::$app->request->get('uid'),
                    'withdate' => \Yii::$app->request->get('dateFrom'),
                    'bydate' => \Yii::$app->request->get('dateBy')
                ], ['class' => 'btn small right download']) ?>
                <div class="clear"></div>
            </div>
        </div>

        <div class="payment-info border-box odn-report-wrap report-item">
            <div class="title">Отчёт по расчёту ОДН <br/> за период за период
                <?= \Yii::$app->request->get('dateFrom'); ?>-<?= \Yii::$app->request->get('dateBy'); ?></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?= Html::a('Печать', [
                    'main/access-file',
                    'print' => 'true',
                    'action' => 'download_counting',
                    'uid' => \Yii::$app->request->get('uid'),
                    'withdate' => \Yii::$app->request->get('dateFrom'),
                    'bydate' => \Yii::$app->request->get('dateBy')
                ], ['class' => 'btn small right print']) ?>
                <?= Html::a('Скачать', [
                    'main/access-file',
                    'print' => 'false',
                    'action' => 'download_counting',
                    'uid' => \Yii::$app->request->get('uid'),
                    'withdate' => \Yii::$app->request->get('dateFrom'),
                    'bydate' => \Yii::$app->request->get('dateBy')
                ], ['class' => 'btn small right download']) ?>
                <div class="clear"></div>
            </div>
        </div>


        </div>

    </div>
</div>

