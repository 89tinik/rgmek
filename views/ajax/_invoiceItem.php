<?php

/* @var $account */

use yii\helpers\Html;

?>

<li>
    <div class="name">
        <?= $account['FullName'] ?>
    </div>
    <div class="price">
        <?= $account['TotalDebt'] ?> руб.
    </div>
    <div class="bts">
        <?= Html::a('Печать', ['main/access-file', 'uid' => $account['UID'], 'print' => 'true', 'action' => 'download_account'], ['class' => 'btn small border print', 'target'=>'_blank']) ?>
        <?= Html::a('Скачать', ['main/access-file', 'uid' => $account['UID'], 'print' => 'false', 'action' => 'download_account'], ['class' => 'btn small border', 'target'=>'_blank']) ?>

    </div>
</li>