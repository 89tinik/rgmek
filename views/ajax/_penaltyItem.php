<?php

/* @var $penalty */

use yii\helpers\Html;

?>

<li>
    <div class="name">
        <?=$penalty['FullName']?>
        <!--strong><?=$penalty['Contractor']?></strong-->
    </div>
    <div class="price">

    </div>
    <div class="bts">
        <?=Html::a('Печать', ['main/access-file', 'uid'=>$penalty['UID'], 'print' => 'true', 'action'=>'download_penalty'],['class'=>'btn small border print', 'target'=>'_blank'])?>
        <?=Html::a('Скачать', ['main/access-file', 'uid'=>$penalty['UID'], 'print' => 'false', 'action'=>'download_penalty'],['class'=>'btn small border download', 'target'=>'_blank'])?>
        <?=Html::a('Скачать', ['main/access-file', 'uid'=>$penalty['UID'], 'uploadWithServer' => 'true', 'print' => 'true', 'action'=>'download_penalty'],['class'=>'btn small border download-mobile', 'target'=>'_blank'])?>
    </div>
</li>