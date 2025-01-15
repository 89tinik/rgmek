<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $files string */
/* @var $draft int */


$files = json_decode($files, true);
$output = '';

foreach ($files as $file) {
    $idx = array_key_first($file);
    if (!empty($file[$idx])) {
        $output .= '<li>' .
            Html::a(basename(mb_convert_encoding($file[$idx], 'UTF-8', 'auto')), ['/' . $file[$idx]], ['target' => '_blank']) .
            Html::button('X', ['class' => 'removeLoadedFile', 'data-idx' => $idx]) .
            '</li>';
    }

}
if (!empty($output)) {
    ?>
    <h3>Прикреплённые файлы</h3>
    <ul id="uploaded-files" ajax-action="<?= Url::to(['draft-termination/remove-file']) ?>" draft-id="<?= $draft ?>">
        <?=$output?>
    </ul>
<?php } ?>
