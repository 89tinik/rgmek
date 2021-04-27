<?php

/* @var $pu */
?>
<div class="device-wrap"><a href="#" class="devices-link"><?= $pu['Name'] ?></a>
    <div style="display: none">
        <table>

            <?php if (!empty($pu['Name'])): ?>
                <tr>
                    <td>Номер прибора учета</td>
                    <td><strong><?= $pu['Name'] ?></strong></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($pu['Type'])): ?>
                <tr>
                    <td>Тип</td>
                    <td><strong><?= $pu['Type'] ?></strong></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($pu['KTT'])): ?>
                <tr>
                    <td>Расчетный коэфицент трансформации из материальных трансформаторов</td>
                    <td><strong><?= $pu['KTT'] ?></strong></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($pu['PlaceInstallation'])): ?>
                <tr>
                    <td>Место установки</td>
                    <td><strong><?= $pu['PlaceInstallation'] ?></strong></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($pu['VerificationYear'])): ?>
                <tr>
                    <td>Год следующей проверки</td>
                    <td><strong><?= $pu['VerificationYear'] ?></strong></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($pu['MPI'])): ?>
                <tr>
                    <td>Межпроверочный интервал (лет)</td>
                    <td><strong><?= $pu['MPI'] ?></strong></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
