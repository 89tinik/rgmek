<?php

/* @var $this yii\web\View */
/* @var $result */

use yii\helpers\Html;

$this->title = 'Начисление и платежи |  ЛК РГМЭК';
$contractFullName = Yii::$app->params['contractFullName'];
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Начисление и платежи</strong><span class="sep"></span>
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
                    <option value="penalty">Расчет пени</option>
                    <option value="odn">Отчет По Расчету ОДН</option>
                    <option>Отчет Начислено Оплачено</option>
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
                <input type="text" value="" id="from_dialog" required="required"/>
            </div>
        </div>
        <div class="field">
            <div class="value">
                <span>По</span>
                <input type="text" value="" id="to_dialog" required="required"/>
            </div>
        </div>
        <input type="submit" class="btn submit-btn get-report" value="Сформировать" />
    </div>
    </form>
</div>

<div class="payment-items">

    <div class="payment-item">

        <div class="arrear-lists white-box aktpp-report-wrap report-item" style="display: none;">
            <div class="white-box-title">Акт приема передачи</div>
            <div class="list">
                <ul>

                </ul>
            </div>
        </div>
        <div class="arrear-lists white-box penalty-report-wrap report-item" style="display: none;">
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
        <!--
        <div class="payment-content border-box" style="display: block;">
            <div class="info-bottom">
                <div class="title">
                    <div class="label">Долг на начало периода</div>
                    <div class="value">-400,00  руб.</div>
                </div>
                <div class="list">
                    <ul>
                        <li>
                            <span class="name">Начисленно</span>
                            <span class="value">5.00 руб.</span>
                        </li>
                        <li>
                            <span class="name">Оплачено</span>
                            <span class="value">5.00 руб.</span>
                        </li>
                    </ul>
                </div>
                <div class="title">
                    <div class="label">Задолжность на конец периода</div>
                    <div class="value">-400,00  руб.</div>
                </div>
            </div>
            <div class="info-table">
                <div class="title">Август 2020</div>
                <div class="table">
                    <table>
                        <tr>
                            <td>
                                Начисленно в августе
                            </td>
                            <td>
                                10.08.2020<br />
                                10.08.2020
                            </td>
                            <td>
                                200.00 руб.<br />
                                200.00 руб.
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Оплаченно в августе
                            </td>
                            <td>
                                12.08.2020<br />
                                14.08.2020<br />
                                14.08.2020
                            </td>
                            <td>
                                50.00 руб.<br />
                                100.00 руб.<br />
                                150.00 руб.
                            </td>
                            <td>
                                Касса РГМЭК<br />
                                Терминал оплаты<br />
                                Отделение банка
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="info-table">
                <div class="title">Сентябрь 2020</div>
                <div class="table">
                    <table>
                        <tr>
                            <td>
                                Начисленно в августе
                            </td>
                            <td>
                                10.08.2020<br />
                                10.08.2020
                            </td>
                            <td>
                                200.00 руб.<br />
                                200.00 руб.
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Оплаченно в августе
                            </td>
                            <td>
                                12.08.2020<br />
                                14.08.2020<br />
                                14.08.2020
                            </td>
                            <td>
                                50.00 руб.<br />
                                100.00 руб.<br />
                                150.00 руб.
                            </td>
                            <td>
                                Касса РГМЭК<br />
                                Терминал оплаты<br />
                                Отделение банка
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        -->
    </div>

</div>