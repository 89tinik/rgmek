<?php
/* @var $month */
?>

<?php
if (is_numeric($month['Volume'][0])){
    foreach ($month['Volume'] as $arr){
        $arr = number_format($arr, 0, ',', ' ');
    }
} else{
    $month['Volume'][0]='0';
}
?>
<tr class="line">
    <td>
        <div class="price"><?=$month['Month'] . "<br>" . implode('/', $month['Year']) ?></div>
    </td>
    <td>
        <div class="price">
            <?=implode('/', $month['Volume']) ?> 
            <?=(is_numeric($month['Volume'][0])) ? 'кВт ч' : 'кВт ч'?>
        </div>
    </td>
</tr>
