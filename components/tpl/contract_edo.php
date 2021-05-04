<?php
/* @var $contract */

use yii\helpers\Html;

?>
<li>
    <?= Html::a($contract['FullName'], ['main/downloadedo', 'uid' => $contract['UID']]) ?>
    <span>Какой-то текст никому не известно</span>
</li>
