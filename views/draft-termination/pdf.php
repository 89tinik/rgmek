<?php
use app\components\CaseHelper;

if ($DirectorOrder != $director_order) {
    $DirectorOrderRP = CaseHelper::getCase($director_order, 1);
}
if ($DirectorPosition != $director_position) {
    $DirectorPositionRP = CaseHelper::getCase($director_position, 1);
}
?>
<h2 align="center">Соглашение о расторжении</h2>
<h3 align="center">Договора энергоснабжения №<?= $contract_id ?></h3>
<table width="100%">
    <tr>
        <td width="50%"><p>г. Рязань</p></td>
        <td width="50%" align="right"><p>«______» _________г.</p></td>
    </tr>
</table>
<p style="text-align: justify;">
    Общество с ограниченной ответственностью "Р-Энергия", именуемое в дальнейшем "Гарантирующий поставщик", в лице
    Начальника управления по работе с юридическими лицами Никитиной Алены Владимировны, действующей на основании
    доверенности от 12.04.2024 г., зарегистрированной в реестре за №62/8-н/62-2024-2-1071, с одной стороны, и
    <?=$user_id?>, именуемое в дальнейшем "Потребитель", в лице <?=$DirectorPositionRP?> <?=$DirectorFullNameRP?>,
    <?= ($DirectorGender == 'Мужской')?'действующего':'действующей'?> на основании <?=$DirectorOrderRP?>, с другой
    стороны, составили настоящее соглашение о нижеследующем:
</p>
<ol style="text-align: justify;">
    <li>Расторгнуть Договор энергоснабжения <?= $contract_id?> (далее – Договор) по соглашению Сторон.</li>
    <li>На момент расторжения договора Гарантирующий поставщик поставил, а Потребитель принял электрическую энергию на сумму
        <?=number_format(intval($ProvidedServicesCost), 0, ',', ' ')?>
        <?=$provided_services_cost_word?>.
    </li>
    <li>Оплата за электрическую энергию Потребителем Гарантирующему поставщику произведена в размере
        <?=number_format(intval($ProvidedServicesCost), 0, ',', ' ')?>  <?=$provided_services_cost_word?>.
    </li>
    <li>Стороны подтверждают, что на момент расторжения договора взаимных претензий не имеют. Стороны не вправе
        требовать возвращения того, что было исполнено ими по договору до момента его расторжения.
    </li>
    <li>Настоящее соглашение составлено в двух экземплярах, имеющих одинаковую
        юридическую силу, и вступает в законную силу с момента подписания его Сторонами.
    </li>

</ol>
<h3 align="center">ПОДПИСИ СТОРОН</h3>
<table width="100%">
    <tr>
        <td width="50%">
            <b>Гарантирующий поставщик</b>
        </td>
        <td width="50%">
            <b>Потребитель</b>
        </td>
    </tr>
    <tr>
        <td width="50%">
            Начальник управления по<br>
            работе с юридическими лицами
        </td>
        <td width="50%">
            <?= CaseHelper::ucfirstCyrillic($director_position) ?>
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td width="25%">
            __________________
        </td>
        <td width="25%">
            А.В. Никитина
        </td>
        <td width="25%">
            __________________
        </td>
        <td width="25%">
            <?= CaseHelper::getInitials($director_full_name) ?>
        </td>
    </tr>
    <tr>
        <td width="25%">

        </td>
        <td width="25%">
            <sup>на основании<br>
                доверенности от<br>
                12.04.2024 г.,<br>
                зарегистрированной в реестре за<br>
                №62/8-н/62-2024-2-1071</sup>
        </td>
        <td width="25%">

        </td>
        <td width="25%">

        </td>
    </tr>
    <tr>
        <td width="25%" align="center">М.П.</td>
        <td width="25%"></td>
        <td width="25%" align="center">М.П.</td>
        <td width="25%"></td>
    </tr>
</table>