<?php
/* @var $month */
?>
<tr class="line">
    <td>
        <div class="price"><?=$month['Month'] . "<br>" . implode('/', $month['Year']) ?></div>
    </td>
    <td>
        <div class="price"><?=implode('/', $month['Volume']) ?> <?=(is_numeric($month['Volume'][0])) ? 'кВт ч' : ''?></div>
    </td>
</tr>
