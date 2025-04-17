<?php
use app\components\CaseHelper;

if ($DirectorOrder != $director_order) {
    $DirectorOrderRP = CaseHelper::getCase($director_order, 1);
}
if ($DirectorPosition != $director_position) {
    $DirectorPositionRP = CaseHelper::getCase($director_position, 1);
}
if ($DirectorFullName != $director_full_name) {
    $DirectorFullNameRP = $director_full_name;
}
?>

<p style="text-align: center; font-size: 14pt;"><b>Дополнительное соглашение</b></p>
<p style="text-align: center; font-size: 12pt;"><b>о внесении изменений в Договор энергоснабжения</b></p>
<p style="text-align: center; font-size: 12pt;"><b>№ <?= $contract_id ?></b></p>
<br />
<table width="100%" style="border-color: #ffffff">
    <tr>
        <td width="50%"  style="border-color: #ffffff"><p style="font-size: 12pt;">г. Рязань</p></td>
        <td width="50%"><p style="font-size: 12pt;text-align: right">"______" ______________ г.</p></td>
    </tr>
</table>
<br />

<p style="text-align: justify; font-size: 12pt;">
    Общество с ограниченной ответственностью "Р-Энергия", именуемое в дальнейшем "Гарантирующий поставщик", в лице
    Начальника управления по работе с юридическими лицами Никитиной Алены Владимировны,
    действующей на основании доверенности от 12.04.2024 г., зарегистрированной в реестре за
    №62/8-н/62-2024-2-1071, с одной стороны, и <?= $user_id ?>, именуемое в дальнейшем "Потребитель", в
    лице <?= $DirectorPositionRP ?> <?= $DirectorFullNameRP ?>, <?= ($DirectorGender == 'Мужской') ? 'действующего' : 'действующей' ?> на основании <?= $DirectorOrderRP ?>, с
    другой стороны, составили настоящее Дополнительное соглашение о нижеследующем:
</p>
<br />

<p style="text-align: justify; font-size: 12pt;">
    Пункт 4.1. Договора энергоснабжения №<?= $contract_id ?> изложить в следующей редакции:
</p>
<br />

<p style="text-align: justify; font-size: 12pt;">
    «Цена договора на весь срок действия договора составляет <?= number_format(intval($contract_price_new), 2, ',', ' ') ?>
    (<?= $price_in_word ?>) рублей, в том числе НДС 20%<?php if($contract_volume_plane_include == 1) : ?> за счет ________________________. Планируемый объем потребления электрической энергии (мощности) составляет <?= $contract_volume_plane ?> кВт.ч.<?php endif;?>» … далее по тексту договора.
</p>
<br />

<p style="text-align: justify; font-size: 12pt;">
    Настоящее Дополнительное соглашение не изменяет иных условий Договора энергоснабжения №<?= $contract_id ?>, является его неотъемлемой частью и распространяет свое действие на отношения сторон с момента подписания.
</p>
<br />

<p style="text-align: justify; font-size: 12pt;">
    Настоящее Дополнительное соглашение составлено в двух экземплярах, по одному для каждой из сторон.
</p>
<br />

<p style="text-align: center; font-size: 12pt;"><b><i>ПОДПИСИ СТОРОН</i></b></p>

<br /><br />
<table width="100%" style="border-color: #ffffff">
    <tr>
        <td width="50%"  style="border-color: #ffffff"><p style="font-size: 12pt;"><b><i>Гарантирующий поставщик</i></b></p></td>
        <td width="50%"><p style="font-size: 12pt;"><b><i>Потребитель</i></b></p></td>
    </tr>
    <tr>
        <td width="50%"  style="border-color: #ffffff"><p style="font-size: 12pt;"></p></td>
        <td width="50%"><p style="font-size: 12pt;"></p></td>
    </tr>
    <tr>
        <td width="50%"  style="border-color: #ffffff"><p style="font-size: 12pt;">Начальник управления по<br/>работе с юридическими<br/>лицами</p></td>
        <td width="50%"><p style="font-size: 12pt;"><?= CaseHelper::ucfirstCyrillic($director_position) ?></p></td>
    </tr>
    <tr>
        <td width="50%"  style="border-color: #ffffff"><p style="font-size: 12pt;"></p></td>
        <td width="50%"><p style="font-size: 12pt;"></p></td>
    </tr>
</table>

<table width="100%" style="border-color: #ffffff">
    <tr>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 12pt;">______________</p></td>
        <td width="30%"><p style="font-size: 12pt;">А.В. Никитина</p></td>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 12pt;">______________</p></td>
        <td width="30%"><p style="font-size: 12pt;"><?= CaseHelper::getInitials($director_full_name) ?></p></td>
    </tr>
    <tr>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 12pt;"></p></td>
        <td width="30%"><p style="font-size: 8pt;">на основании доверенности от 12.04.2024 г., зарегистрированной в реестре за №62/8-н/62-2024-2-1071</p></td>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 12pt;"></p></td>
        <td width="30%"><p style="font-size: 12pt;"></p></td>
    </tr>
    <tr>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 8pt;text-align: center">М.П.</p></td>
        <td width="30%"><p style="font-size: 12pt;"></p></td>
        <td width="20%"  style="border-color: #ffffff"><p style="font-size: 8pt;text-align: center">М.П.</p></td>
        <td width="30%"><p style="font-size: 12pt;"></p></td>
    </tr>
</table>

