<?php

/* @var $doc */

use yii\helpers\Html;

?>
<tr <?php if (empty($doc['Path'])) echo 'class="dis"' ?>>
    <td><?php if (!empty($doc['Date'])) echo $doc['Date'] ?></td>
    <td><?= $doc['Name'] ?></td>
    <td><a href="#popap-info-tehadd" data-fancybox><img src="/images/icon.svg" width="50"/></a></td>
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
</tr>
