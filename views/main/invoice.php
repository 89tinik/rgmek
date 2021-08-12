<?php

/* @var $this yii\web\View */

/* @var $result */
/* @var $withDate */
/* @var $withDateDetail */

/* @var $byDate */
/* @var $typeOrder */
/* @var $invoices */

use yii\helpers\Html;

$this->title = 'Счета |  ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Счета</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
    </div>
</div>


<div class="payment-items">
    <div class="payment-item">

        <div class="pack-report-wrap white-box">
            <div class="white-box-title">Текущие счета и расчетные документы</div>
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
                    <tr>
                        <td>
                            <div class="checkbox-item">
                                <strong>Детализация счета по договору<br/><?= $result['Contract']['FullName'] ?>
                                </strong>
                            </div>
                        </td>
                        <td>
                        </td>
                        <td>
                            <div class="bts">
                                <!--a href="#" class="btn small border">Просмотр</a-->
                                <?= Html::a('Печать', [
                                    'main/access-file',
                                    'print' => 'true',
                                    'action' => 'download_report_detal',
                                    'uid' => \Yii::$app->request->get('uid'),
                                    'withdate' => $withDateDetail,
                                    'bydate' => $byDate
                                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                                <?= Html::a('Скачать', [
                                    'main/access-file',
                                    'print' => 'false',
                                    'action' => 'download_report_detal',
                                    'uid' => \Yii::$app->request->get('uid'),
                                    'withdate' => $withDate,
                                    'bydate' => $byDate
                                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr class="tr-odn">
                        <td>
                            <div class="checkbox-item">
                                <strong>Отчёт по расчёту ОДН</strong>
                            </div>
                        </td>
                        <td>
                        </td>
                        <td>
                            <div class="bts">
                                <?= Html::a('Печать', [
                                    'main/access-file',
                                    'print' => 'true',
                                    'action' => 'download_counting',
                                    'uid' => \Yii::$app->request->get('uid'),
                                    'withdate' => $withDate,
                                    'bydate' => $byDate
                                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                                <?= Html::a('Скачать', [
                                    'main/access-file',
                                    'print' => 'false',
                                    'action' => 'download_counting',
                                    'uid' => \Yii::$app->request->get('uid'),
                                    'withdate' => $withDate,
                                    'bydate' => $byDate
                                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
    </div>

</div>


<div class="payment-filter white-box">
    <form class="get-order-form">
        <div class="group">
            <div class="field">
                <div class="label">Счета и расчетные документы за предыдущие периоды:</div>
                <div class="value">
                    <select class="styler select__default type-order" required="required">
                        <option></option>
                        <option value="detail">Детализация счёта</option>
                        <option value="aktpp">Акт приема передачи э/э</option>
                        <option value="odn">Отчет по расчету ОДН</option>
                        <option value="penalty">Расчет пени</option>
                        <option value="invoices"  <?=($typeOrder == 'invoices')?'selected="selected"':'';?>>Счета</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="group large">
            <div class="label">Выбрать период:</div>
            <div class="field">
                <div class="value">
                    <span>с</span>
                    <input type="text" value="<?= $withDate ?>" id="from_dialog"
                           required="required" autocomplete="off" readonly="readonly"/>
                </div>
            </div>
            <div class="field">
                <div class="value">
                    <span>По</span>
                    <input type="text" value="<?= $byDate ?>" id="to_dialog"
                           required="required" autocomplete="off" readonly="readonly"/>
                </div>
            </div>
            <input type="submit" class="btn submit-btn get-report" value="Сформировать"/>
        </div>
    </form>
</div>

<div class="payment-items">
    <div class="payment-item">




        <div class="arrear-lists white-box invoices-report-wrap report-item big-name" style="display: <?=(!empty($invoices))?'block':'none';?>;">
            <div class="white-box-title">Счета</div>
            <div class="list">
                <ul>
                    <?php
                    if (isset($invoices['Invoice'])) {
                        if (isset($invoices['Invoice']['FullName'])) {
                            echo $this->render('_invoiceItem', [
                                'invoice' => $invoices['Account']
                            ]);
                        } else {
                            foreach ($invoices['Invoice'] as $arr) {
                                echo $this->render('_invoiceItem', [
                                    'invoice' => $arr
                                ]);
                            }
                        }
                    } else {
                        echo '<li><h3>За выбранный период документы отсутствуют.</h3></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="arrear-lists white-box penalty-report-wrap report-item  big-name" style="display: none;">
            <div class="white-box-title">Пени</div>
            <div class="list">
                <ul>

                </ul>
            </div>
        </div>

        <div class="payment-info border-box detail-report-wrap report-item" style="display: none">
            <div class="title">Детализация счета по договору<br/><?= $result['Contract']['FullName'] ?><br/>за
                период <?= $withDate ?>-<?= $byDate ?></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?= Html::a('Печать', [
                    'main/access-file',
                    'print' => 'true',
                    'action' => 'download_report_detal',
                    'uid' => \Yii::$app->request->get('uid')
                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                <?= Html::a('Скачать', [
                    'main/access-file',
                    'print' => 'false',
                    'action' => 'download_report_detal',
                    'uid' => \Yii::$app->request->get('uid')
                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
                <div class="clear"></div>
            </div>
        </div>

        <div class="payment-info border-box odn-report-wrap report-item" style="display: none">
            <div class="title">Отчёт по расчёту ОДН <br/> за период за период
                <?= $withDate ?>-<?= $byDate ?></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?= Html::a('Печать', [
                    'main/access-file',
                    'print' => 'true',
                    'action' => 'download_counting',
                    'uid' => \Yii::$app->request->get('uid')
                ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
                <?= Html::a('Скачать', [
                    'main/access-file',
                    'print' => 'false',
                    'action' => 'download_counting',
                    'uid' => \Yii::$app->request->get('uid')
                ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
                <div class="clear"></div>
            </div>
        </div>
        <div class="arrear-lists white-box aktpp-report-wrap report-item big-name" style="display: none;">
            <div class="white-box-title">Акт приема передачи</div>
            <div class="list">
                <ul>

                </ul>
            </div>
        </div>

    </div>

</div>