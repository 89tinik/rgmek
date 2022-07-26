<?php
/* @var $line */
?>
<tr class="line">
    <td>
        <div class="price"><?=$line['Month']?></div>
    </td>
    <td>
        <div class="price"><?=$line['Year']?></div>
    </td>
    <td>
        <div class="price"><?=$line['Volume']?> <?=(is_numeric($line['Volume'])) ? 'кВт ч' : ''?></div>
    </td>

<!--    <td>-->
<!--        <div class="checkbox-item">-->
<!--            <strong>--><?//=$line['Indication']?><!--</strong>-->
<!--            --><?php //if (!empty($line['Origin'])) echo '<span>' . $line['Origin'] . '</span>'; ?>
<!--        </div>-->
<!--    </td>-->
</tr>
