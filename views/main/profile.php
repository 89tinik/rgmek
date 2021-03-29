<?php

/* @var $this yii\web\View */

$this->title = 'Профиль';

var_dump($result);

?>
<div class="page-heading">
    <h1 class="title">Профиль потребителя</h1>
</div>

<div class="profile-left">
    <div class="profile-details">
        <ul>
            <li>
                <span class="label">Потребитель</span>
                <span class="value"><?= $result['name'] ?></span>
            </li>
            <li>
                <span class="label">Юр. адрес</span>
                <span class="value"><?= $result['Jaddress'] ?></span>
            </li>
            <li>
                <span class="label">Почтовый адрес</span>
                <span class="value"><?= $result['Maddress'] ?></span>
            </li>
            <li>
                <span class="label">Эл. почта:</span>
                <span class="value"><?= $result['Email'][0] ?></span>
            </li>
            <li>
                <span class="label">ФИО руководителя:</span>
                <span class="value"><?= implode(' ', $result['FIO']); ?></span>
            </li>
            <li>
                <span class="label">Телефоны:</span>
                <span class="value">
                    <?php
                    $phoneCity = '';
                    foreach ($result['PhoneCity'] as $arr){
                        if (empty($restrictionNotice)){
                            $phoneCity = $arr['Value'];
                        } else {
                            $phoneCity .= ', '.$arr['Value'];
                        }
                    }
                    echo $phoneCity;
                    ?>
                </span>
            </li>
            <li>
                <span class="label">Контакты для уведомлений об ограничении:</span>
                <span class="value">
                    <?php
                    $restrictionNotice = '';
                    foreach ($result['RestrictionNotice'] as $arr){
                        if (empty($restrictionNotice)){
                            $restrictionNotice = $arr['Value'];
                        } else {
                            $restrictionNotice .= ', '.$arr['Value'];
                        }
                    }
                    echo $restrictionNotice;
                    ?>

                </span>
            </li>
            <li class="last">
                <span class="label">Ответственные лица по стороны Потребителя:</span>
                <div class="more-list">
                    <div class="btn small border">Свернуть список</div>
                    <div class="more-list-popup" style="display: none;">
                        <?php
                        foreach ($result['Additional']['ProfileAdditional'] as $pa) {
                            $outputContacts = '';
                            foreach ($pa['Contacts'] as $c => $v) {
                                $outputContacts .= '  /  ' . $v;
                            }
                            echo '<p>' . $pa['Surname'] . $pa['Name'][0] . '.' . $pa[''] . '. ' . $outputContacts . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="info-warning">
        <strong>Внимание!</strong>
        В случае обнаружения или ошибок изменения контактной информации Потребителя, необходимо сообщить об этом в офис
        ООО”РГМЭК” либо написать обращение
    </div>
</div>

<div class="profile-right">
    <div class="profile-status white-box">
        <div class="white-box-title">Электронный документооборот</div>
        <div class="status-text">
            Вы можете настроить электронный документооборот с ООО “РГМЭК” через специализированных операторов. Подробнее
            читайте в разделе электронный документооборот
        </div>
        <div class="status-control">
            <?php if (!empty($result['Specifications']['EmailAccount1'])){?>
            <div class="label active">Статус: <span>Активно</span></div>
            <div class="bts">
                <a href="#" class="btn small border">Активно</a>
                <a href="#" class="lnk">Подробнее</a>
            </div>
            <?php }else{?>
                <div class="label">Статус: <span>Не активно</span></div>
                <div class="bts">
                    <a href="#" class="btn small">Активировать</a>
                    <a href="#" class="lnk">Подробнее</a>
                </div>
            <?php} ?>
        </div>
    </div>
    <div class="profile-status white-box">
        <div class="white-box-title">Получение счетов по электроной почте</div>
        <div class="status-text">
            Вы можете настроить уведомления по электронной почтес ООО “РГМЭК” через специализированных операторов.
        </div>
        <div class="status-control">
            <?php if (!empty($result['Specifications']['EmailAccount1'])):?>
                <div class="label active">Статус: <span>Активно</span></div>
                <div class="bts">
                    <a href="#" class="btn small border">Активно</a>
                    <a href="#" class="lnk">Подробнее</a>
                </div>
            <?php else:?>
                <div class="label">Статус: <span>Не активно</span></div>
                <div class="bts">
                    <a href="#" class="btn small">Активировать</a>
                    <a href="#" class="lnk">Подробнее</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>