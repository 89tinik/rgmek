<?php

/* @var $pack */
/* @var $action */

use yii\helpers\Html;
?>
<tr>
    <td>
        <div class="checkbox-item">
                <strong><?=$pack['FullName']?></strong>
                <!--span>Счет на оплату от: 01.01.2013</span-->
        </div>
    </td>
    <td>
        <?php if ($pack['TotalDebt']) echo '<div class="price">'. $pack['TotalDebt'] .' руб.</div>';?>
    </td>
    <td>
        <!--a href="#" class="btn small border right">Просмотр</a-->
        <div class="bts">
            <?=Html::a('Печать', ['main/access-file', 'uid'=>$pack['UID'], 'print' => 'true', 'action'=>$action],['class'=>'btn small border right'])?>
            <?=Html::a('Скачать', ['main/access-file', 'uid'=>$pack['UID'], 'print' => 'false', 'action'=>$action],['class'=>'btn small border right'])?>

        </div>
    </td>
</tr>
