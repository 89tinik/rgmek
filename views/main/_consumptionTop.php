<?php
/* @var $month */
?>
<tr class="line">
    <td>
        <div class="price"><?=$month['Month']?></div>
    </td>
    <td>
        <div class="price"><?=$month['Year']?></div>
    </td>
    <td>
        <div class="price"><?=$month['Volume']?> <?=(is_numeric($month['Volume'])) ? 'кВт ч' : ''?></div>
    </td>
</tr>
