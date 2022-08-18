<?php

/* @var $pu */
/* @var $currentTU */
/* @var $model */
/* @var $UIDContract */

use yii\helpers\Html;

?>

<div class="objects-item wrap-object history-wrap <?= ($currentTU == $pu['UIDTU']) ? 'open' : '' ?>">
    <div class="objects-head">
        <!--div class="subname">3 прибора учета</div-->
        <div class="name"><a href="#"><?= $pu['FullName'] ?></a></div>
        <div class="date-install"><b>Дата установки: <?= $pu['InstallationDate'] ?></b></div>
        <div class="info"><?= $pu['Purpose'] ?></div>
        <div class="info blue"><?= $pu['KTTName'] ?></div>
    </div>
    
    
    <?php if (isset($pu['Line'])) {?>
    <div class="objects-body" style="display: <?= ($currentTU == $pu['UIDTU']) ? 'block' : 'none' ?>;">
        <div class="invoice-table history">
            <table class="tab-invoice">
                <tbody>
                    <tr>
                        <td>Месяц</td>
                        <td>Показание / источник показаний</td>
                        <td>Расход, кВт ч</td>
                    </tr>
                <?php

                    if (isset($pu['Line']['Date'])) {
                        echo $this->render('_historyLine', [
                            'line' => $pu['Line']
                        ]);
                    } else {
                        foreach ($pu['Line'] as $arr) {
                            echo $this->render('_historyLine', [
                                'line' => $arr
                            ]);
                        }
                    }
                ?>
                </tbody>
            </table>

        </div>
    </div>
    <div class="objects-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?= ($currentTU == $pu['UIDTU']) ? 'Свернуть' : 'Развернуть' ?></span>
        </a>
    </div>
    <?php } ?>
</div>
