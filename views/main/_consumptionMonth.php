<?php
/* @var $line */
?>
<tr class="line">
    <td>
        <div class="price"><?=$line['Month']?></div>
    </td>
    <td>
        <div class="price"><?=implode('/', $line['Year'])?></div>
    </td>
    <td>
        <div class="price"><?=implode('/', $line['Volume'])?> <?=(is_numeric($line['Volume'][0])) ? 'кВт ч' : ''?></div>
        <?php if (!empty ($line['CalculationMethod'])):?>
        <span class="colculation-name"><?=$line['CalculationMethod']?></span>
            <a class="btn small border colculation-popup-link">?</a>
            <div class="colculation-popup">
                <h3><?=$line['CalculationMethodName']?></h3>
                <p><?=$line['CalculationMethodInitialData']?></p>
            </div>
        <?php endif; ?>
    </td>

<!--    <td>-->
<!--        <div class="checkbox-item">-->
<!--            <strong>--><?//=$line['Indication']?><!--</strong>-->
<!--            --><?php //if (!empty($line['Origin'])) echo '<span>' . $line['Origin'] . '</span>'; ?>
<!--        </div>-->
<!--    </td>-->
</tr>
