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

        <a href="#" class="btn small border">Печать</a>
        <?=Html::a('Скачать', ['main/decoding', 'uid'=>'10cbd9b8-f40d-11ea-8e6e-002590c76e1b'],['class'=>'btn small border'])?>
    </div>
</li>