<?php

/* @var $act*/

use yii\helpers\Html;

?>

<li>
    <div class="name">
        <?=$act['FullName']?>
    </div>
    <div class="price">

    </div>
    <div class="bts">
        <?=Html::a('Печать', ['main/access-file', 'uid'=>$act['UID'], 'print' => 'true', 'action'=>'download_act_reception_transfer'],['class'=>'btn small border', 'target'=>'_blank'])?>
        <?=Html::a('Скачать', ['main/access-file', 'uid'=>$act['UID'], 'print' => 'false', 'action'=>'download_act_reception_transfer'],['class'=>'btn small border', 'target'=>'_blank'])?>

    </div>
</li>