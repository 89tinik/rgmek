<?php

/* @var $doc */
/* @var $i */

use yii\helpers\Html;

?>
<tr <?php if (empty($doc['Path'])) echo 'class="dis"' ?>>
    <td><?php if (!empty($doc['Date'])) echo $doc['Date'] ?></td>
    <td class="name"><?= $doc['Name'] ?></td>
    
    <td>

        <?php
        if (!empty($doc['Path'])) {
            echo Html::a('Скачать', [
                'main/access-tehadd-file',
                'print' => 'true',
                'action' => 'download_report_consumption',
                'uid' => \Yii::$app->request->get('uid'),
                'path' => $doc['Path'],
                'name' => $doc['Name']
            ], ['class' => 'btn full', 'target' => '_blank']);
        } else {
            echo '<button class="btn full disable">Скачать</button>';
        }
        ?>
    </td>
    <td class="popup"><a data-fancybox class="btn small border" href="#popap-info-tehadd-<?=$i?>">?</a></td>
</tr>
