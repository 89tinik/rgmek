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

<p style="text-align: center; font-size: 14pt;"><b>Соглашение о расторжении</b></p>
<p style="text-align: center; font-size: 12pt;"><b>Договора энергоснабжения №<?= $contract_id ?></b></p>
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
    Начальника управления по работе с юридическими лицами Никитиной Алены Владимировны, действующей на основании
    доверенности от 12.04.2024 г., зарегистрированной в реестре за №62/8-н/62-2024-2-1071, с одной стороны, и
    <?=$user_id?>, именуемое в дальнейшем "Потребитель", в лице <?=$DirectorPositionRP?> <?=$DirectorFullNameRP?>,
    <?= ($DirectorGender == 'Мужской')?'действующего':'действующей'?> на основании <?=$DirectorOrderRP?>, с другой
    стороны, составили настоящее соглашение о нижеследующем:
</p>
<ol style="text-align: justify;">
    <li><p style="text-align: justify; font-size: 12pt;">Расторгнуть Договор энергоснабжения <?= $contract_id?> (далее – Договор) по соглашению Сторон.</p></li>
    <li><p style="text-align: justify; font-size: 12pt;">На момент расторжения договора Гарантирующий поставщик поставил, а Потребитель принял электрическую энергию на сумму
        <?=number_format(intval($ProvidedServicesCost), 0, ',', ' ')?>
            <?=$provided_services_cost_word?>.</p>
    </li>
    <li><p style="text-align: justify; font-size: 12pt;">Оплата за электрическую энергию Потребителем Гарантирующему поставщику произведена в размере
            <?=number_format(intval($ProvidedServicesCost), 0, ',', ' ')?>  <?=$provided_services_cost_word?>.</p>
    </li>
    <li><p style="text-align: justify; font-size: 12pt;">Стороны подтверждают, что на момент расторжения договора взаимных претензий не имеют. Стороны не вправе
            требовать возвращения того, что было исполнено ими по договору до момента его расторжения.</p>
    </li>
    <li><p style="text-align: justify; font-size: 12pt;">Настоящее соглашение составлено в двух экземплярах, имеющих одинаковую
            юридическую силу, и вступает в законную силу с момента подписания его Сторонами.</p>
    </li>
</ol>
<br/>
<br/>
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

