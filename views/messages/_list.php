<?php

use yii\widgets\ListView;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_item', // Представление для одного элемента списка
    'summary' => false, // Убираем строку с информацией о количестве записей
    'pager' => [
        'options' => ['class' => 'hidden'], // Добавляем класс для скрытия пагинации
    ],
]) ?>