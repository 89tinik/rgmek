<h2 style="text-align: center">Обращение в ООО «Р-Энергия»</h2>
<p><b>Дата обращения:</b> <?= $date ?> час</p>
<p><b>Потребитель:</b> <?= $user ?></p>
<p><b>Номер договора энергоснабжения:</b> <?= $contract ?></p>
<p><b>Тема обращения:</b> <?= $subject ?></p>
<p><b>Текст обращения:<br></b> <?= $message ?></p>
<p></p>
<?php if (!empty($filesUploadNames)) : ?>
    <p><b>Прикрепленные файлы:</b> <?= basename($filesUploadNames) ?></p>
<?php endif; ?>

<?php if (!empty($contactName)) : ?>
    <p><b>Контактное лицо по обращению:</b> <?= $contactName ?></p>
<?php endif; ?>

<?php if (!empty($phone)) : ?>
    <p><b>Телефон:</b> <?= $phone ?></p>
<?php endif; ?>

<?php if (!empty($email)) : ?>
    <p><b>Email:</b> <?= $email ?></p>
<?php endif; ?>