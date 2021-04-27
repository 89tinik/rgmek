<?php

/* @var $invoice */

use yii\helpers\Html;
?>
<li>
    <div class="name">
        Какой-то текст ни кому не известно
        <strong><?=$invoice['FullName']?></strong>
    </div>
    <div class="price"><?= (!empty($invoice['TotalDebt'])) ? $invoice['TotalDebt'].' руб.' : 0 ;?></div>
    <div class="bts">

        <?=Html::a('Печать', ['main/access-file', 'uid'=>$invoice['UID'], 'print' => 'true'],['class'=>'btn small border'])?>
        <?=Html::a('Скачать', ['main/access-file', 'uid'=>$invoice['UID'], 'print' => 'false'],['class'=>'btn small border'])?>
    </div>
</li>