<?php
/* @var $model app\models\Messages */

use yii\helpers\Html;

?>
<div class="message-item payment-filter white-box">
    <h3 class="message-title"><?= Html::encode($model->subject->title) ?> <span><?= Html::encode($model->admin_num) ?></span><?php if ($model->new) echo '<sup>NEW</sup>';?></h3>

    <p><?= Html::encode($model->published) ?></p>
    <div class="message-status status-<?=$model->status->id?> btn" ><?= Html::encode($model->status->status) ?></div>
    <p><?= Html::a('Подробнее', ['messages/update', 'id'=>$model->id]) ?></p>
</div>
