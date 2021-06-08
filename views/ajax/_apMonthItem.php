<?php

/* @var $apMonth */

?>
<div class="info-table">
    <div class="title"><?= $apMonth['Value'] ?></div>
    <div class="table">
        <table>
            <?php
            if ($apMonth['Accrual']) {
                if ($apMonth['Accrual']['Number']) {
                    echo '<tr><td>Начислено</td><td>' . $apMonth['Accrual']['Date'] . '</td> <td>' . $apMonth['Accrual']['Number'] . ' руб.</td><td>' . $apMonth['Accrual']['Value'] . '</td></tr>';
                } else {
                    $outputAccrualArr = [];
                    foreach ($apMonth['Accrual'] as $arr) {
                        $outputAccrualArr['Value'][] = $arr['Value'];
                        $outputAccrualArr['Date'][] = $arr['Date'];
                        $outputAccrualArr['Number'][] = $arr['Number'];
                    }
                    echo '<tr><td>Начислено</td><td>' . implode('<br/>', $outputAccrualArr['Date']) . '</td> <td>' . implode(' руб.<br/>', $outputAccrualArr['Number']) . ' руб.</td><td>' . implode('<br/>', $outputAccrualArr['Value']) . '</td></tr>';

                }

            }

            if ($apMonth['Payment']) {
                if ($apMonth['Payment']['Number']) {
                    echo '<tr><td>Оплачено</td><td>' . $apMonth['Payment']['Date'] . '</td> <td>' . $apMonth['Payment']['Number'] . ' руб.</td><td>' . $apMonth['Payment']['Value'] . '</td></tr>';
                } else {
                    $outputPaymentArr = [];
                    foreach ($apMonth['Payment'] as $arr) {
                        $outputPaymentArr['Value'][] = $arr['Value'];
                        $outputPaymentArr['Date'][] = $arr['Date'];
                        $outputPaymentArr['Number'][] = $arr['Number'];
                    }
                    echo '<tr><td>Оплаченно</td><td>' . implode('<br/>', $outputPaymentArr['Date']) . '</td> <td>' . implode(' руб.<br/>', $outputPaymentArr['Number']) . ' руб.</td><td>' . implode('<br/>', $outputPaymentArr['Value']) . '</td></tr>';

                }

            }
            ?>

        </table>
    </div>
</div>