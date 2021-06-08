<?php

/* @var $this yii\web\View */
/* @var $result */

use yii\helpers\Html;

$this->title = 'Начисление и платежи |  ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Начисления и платежи</strong><span class="sep"></span>
        <span>Договор <span class="name-sidebar"></span></span>
    </div>
</div>

<div class="payment-filter white-box">
    <form class="get-order-form">
    <div class="group small">
        <div class="field">
            <div class="label">Выбрать документ:</div>
            <div class="value">
                <select class="styler select__default type-order" required="required">
                    <option></option>
                    <option value="accruedpaid">Отчет о начислениях и платежах</option>
                    <option value="detail">отчёт Детализация отчёта</option>
                    <option value="aktpp">Акт приема-передачи э/энергии</option>
                    <option value="penalty">Расчет пени</option>
                    <option value="odn">Отчет по расчету ОДН</option>
                </select>
            </div>
        </div>
    </div>
    <div class="group large">
        <div class="label">Выбрать период:</div>
        <div class="field">
            <div class="value">
                <span>с</span>
                <input type="text" value="<?=\Yii::$app->request->get('dateFrom');?>" id="from_dialog" required="required"/>
            </div>
        </div>
        <div class="field">
            <div class="value">
                <span>По</span>
                <input type="text" value="<?=\Yii::$app->request->get('dateBy');?>" id="to_dialog" required="required"/>
            </div>
        </div>
        <input type="submit" class="btn submit-btn get-report" value="Сформировать" />
    </div>
    </form>
</div>

<div class="payment-items">

    <div class="payment-item">

        <div class="arrear-lists white-box aktpp-report-wrap report-item big-name" style="display: none;">
            <div class="white-box-title">Акт приема передачи</div>
            <div class="list">
                <ul>

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
            <div class="title"></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?=Html::a('Печать', ['main/access-file', 'print' => 'true', 'action'=>'download_report_detal'],['class'=>'btn small right print'])?>
                <?=Html::a('Скачать', ['main/access-file', 'print' => 'false', 'action'=>'download_report_detal'],['class'=>'btn small right download'])?>
                <div class="clear"></div>
            </div>
        </div>

        <div class="payment-info border-box odn-report-wrap report-item" style="display: none">
            <div class="title"></div>
            <div class="bts">
                <!--a href="#" class="btn small border">Просмотр</a-->
                <?=Html::a('Печать', ['main/access-file', 'print' => 'true', 'action'=>'download_counting'],['class'=>'btn small right print'])?>
                <?=Html::a('Скачать', ['main/access-file', 'print' => 'false', 'action'=>'download_counting'],['class'=>'btn small right download'])?>
                <div class="clear"></div>
            </div>
        </div>

        <div class="payment-content border-box accruedpaid-report-wrap report-item" style="display: none;">

        </div>

    </div>

</div>