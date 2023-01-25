<?php

/* @var $this yii\web\View */
/* @var $page \app\models\Page */


use yii\helpers\Html;

$this->title = 'Помощь | ЛК РГМЭК';
?>
<?php
    echo '<h1>'.$page->title.'</h1>';
    echo $page->content;
?>
