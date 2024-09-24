<?php
/* @var $model app\models\Messages */

use yii\helpers\Html;

$date = (!empty($model->published)) ? $model->published : $model->created;
?>
<div class="white-box">
    <h3>Обращение</h3>
<table >
    <colgroup>
        <col width="45%">
        <col width="20%">
        <col width="35%">
    </colgroup>
    <tbody>

<tr>
    <td>
        <div class="message-title">
            <?= Html::encode($model->subject->title) ?>
            <span><?php if (!empty($model->admin_num)) echo '№' . Html::encode($model->admin_num) ?></span>
            <?php if ($model->new) echo '<sup>NEW</sup>';?>
            <!--span>Счет на оплату от: 01.01.2013</span-->
        </div>
    </td>
    <td><p><?= Yii::$app->formatter->asDate($date, 'php:d.m.Y') ?></p>    </td>
    <td>
        <!--a href="#" class="btn small border right">Просмотр</a-->
        <div class="bts">
            <div class="message-status status-<?=$model->status->id?> btn small" ><?= Html::encode($model->status->status) ?></div>
            <?= Html::a('Подробнее', ['messages/update', 'id'=>$model->id], ['class'=> 'btn small border right message-btn']) ?>
        </div>
    </td>
</tr>
    </tbody>
</table>
</div>