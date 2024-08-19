<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $themeModel app\models\MessageThemes */
/* @var $messageModel app\models\MessageThemes */
/* @var $userModel app\models\User */

$this->title = $themeModel->title;
$this->params['breadcrumbs'][] = ['label' => 'Message Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="message-themes-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $themeModel->content ?>
    <?= $this->render('_formCreate', [
        'model' => $messageModel,
        'subject' => $themeModel->id,
        'userModel' => $userModel,
    ]) ?>
</div>
