<?php
/* @var $line */
?>
<tr class="line">
    <td>
        <div class="price"><?=$line['Month'] . ' ' . $line['Year']?></div>
    </td>
    <td>
        <div class="checkbox-item">
            <strong><?=$line['Indication']?></strong>
            <?php if (!empty($line['Origin'])) echo '<span>' . $line['Origin'] . '</span>'; ?>
        </div>
    </td>
    <td>
        <div class="price"><?=$line['Volume']?> <?=(is_numeric($line['Volume'])) ? '<span class="kvt">кВт ч</span>' : ''?></div>
    </td>

</tr>
