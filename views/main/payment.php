<?php

/* @var $this yii\web\View */
/* @var $result */

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
    <div class="group small">
        <div class="field">
            <div class="label">Выбрать тип отчета:</div>
            <div class="value">
                <select class="styler select__default">
                    <option>Из списка</option>
                    <option>Из списка 2</option>
                    <option>Из списка 3</option>
                    <option>Из списка 4</option>
                    <option>Из списка 5</option>
                </select>
            </div>
        </div>
    </div>
    <div class="group large">
        <div class="label">Выбрать период:</div>
        <div class="field">
            <div class="value">
                <span>с</span>
                <input type="text" value="17.05.2019" />
            </div>
        </div>
        <div class="field">
            <div class="value">
                <span>По</span>
                <input type="text" value="17.05.2019" />
            </div>
        </div>
        <input type="submit" class="btn submit-btn" value="Сформировать" />
    </div>
</div>

<div class="payment-items">

    <div class="payment-item">
        <div class="payment-info border-box">
            <div class="title">Детализация счета по договору № 0509 за период 17.05.2019 - 27.05.2019</div>
            <div class="bts">
                <a href="#" class="btn small border">Просмотр</a>
                <a href="#" class="btn small right">Печать</a>
                <a href="#" class="btn small right">Скачать</a>
            </div>
        </div>
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
    </div>

</div>