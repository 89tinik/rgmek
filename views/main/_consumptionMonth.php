<?php
/* @var $line */
?>

<?php
if (is_numeric($line['Volume'][0])){
    foreach ($line['Volume'] as &$arr){
        $arr = number_format($arr, 0, ',', ' ');
    }
}
?>
<tr class="line">
    <td>
        <div class="price"><?=$line['Month'] . "<br>" . implode('/', $line['Year']) ?></div>
    </td>
    <td>
        <div class="price"><?=implode('/', $line['Volume'])?> <?=(is_numeric($line['Volume'][0])) ? 'кВт ч' : 'кВт ч'?></div>
    </td>
    <td>
        <div class="price"><?=implode('/', $line['VolumeR'])?> <?=(is_numeric($line['VolumeR'][0])) ? 'кВт ч' : 'кВт ч'?></div>
    </td>
<!--    <td>-->
<!--        --><?php //if (!empty ($line['CalculationMethod'])):?>
<!--            <span class="colculation-name">--><?php //=$line['CalculationMethod']?><!--</span>-->
<!--                <a class="btn small border colculation-popup-link">?</a>-->
<!--                <div class="colculation-popup">-->
<!--                    <h3>--><?php //=$line['CalculationMethodName']?><!--</h3>-->
<!--                    <p>--><?php //=$line['CalculationMethodInitialData']?><!--</p>-->
<!--                </div>-->
<!--        --><?php //endif; ?>
<!--    </td>-->

<!--    <td>-->
<!--        <div class="checkbox-item">-->
<!--            <strong>--><?//=$line['Indication']?><!--</strong>-->
<!--            --><?php //if (!empty($line['Origin'])) echo '<span>' . $line['Origin'] . '</span>'; ?>
<!--        </div>-->
<!--    </td>-->
</tr>
