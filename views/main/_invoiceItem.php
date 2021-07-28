<?php

/* @var $invoice */

use yii\helpers\Html;
?>
<li>
    <div class="name">
        <strong><?=$invoice['FullName']?></strong>
    </div>
    <div class="price"><?= (!empty($invoice['TotalDebt'])) ? $invoice['TotalDebt'].' руб.' : 0 ;?></div>
    <div class="bts">

        <?=Html::a('Печать', ['main/access-file', 'uid'=>$invoice['UID'], 'print' => 'true', 'action'=>'download_account'],['class'=>'btn small border', 'target'=>'_blank'])?>
        <?=Html::a('Скачать', ['main/access-file', 'uid'=>$invoice['UID'], 'print' => 'false', 'action'=>'download_account'],['class'=>'btn small border', 'target'=>'_blank'])?>
    </div>
</li>