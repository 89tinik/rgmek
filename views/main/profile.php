<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Профиль';

?>
<div class="page-heading">
    <h1 class="title">Профиль потребителя</h1>
</div>

<div class="profile-left">
    <div class="profile-details">
        <ul>
		<?php if (!empty($result['Name'])):?>
            <li>
                <span class="label">Потребитель</span>
                <span class="value"><?= $result['Name'] ?></span>
            </li>
		<?php endif;?>
		<?php if (!empty($result['Jaddress']['Value'])):?>
            <li>
                <span class="label">Юр. адрес</span>
                <span class="value"><?= $result['Jaddress']['Value'] ?></span>
            </li>
		<?php endif;?>
		<?php if (!empty($result['Maddress']['Value'])):?>
            <li>
                <span class="label">Почтовый адрес</span>
                <span class="value"><?= $result['Maddress']['Value'] ?></span>
            </li>
		<?php endif;?>
		<?php if (!empty($result['Email'][0]['Value'])):?>
            <li>
                <span class="label">Эл. почта:</span>
                <span class="value"><?= $result['Email'][0]['Value'] ?></span>
            </li>
		<?php endif;?>
		<?php if (!empty($result['FIO'])):?>
            <li>
                <span class="label">ФИО руководителя:</span>
                <span class="value"><?= implode(' ', $result['FIO']); ?></span>
            </li>
		<?php endif;?>
		<?php if (!empty($result['PhoneCity'])):?>
            <li>
                <span class="label">Телефоны:</span>
                <span class="value">
                    <?php
					if (is_array($result['PhoneCity']) && !isset($result['PhoneCity']['Value'])){
						$phoneCity = '';
						foreach ($result['PhoneCity'] as $arr){
							if (empty($phoneCity)){
								$phoneCity = $arr['Value'];
							} else {
								$phoneCity .= ', '.$arr['Value'];
							}
						}
					} else {
						$phoneCity = $result['PhoneCity']['Value'];
					}
                    echo $phoneCity;
                    ?>
                </span>
            </li>
			<?php endif;?>
			<?php if (!empty($result['RestrictionNotice'])):?>
            <li class="big">
                <span class="label">Контакты для уведомлений об ограничении:</span>
                <span class="value">
                    <?php
					if (is_array($result['RestrictionNotice']) && !isset($result['RestrictionNotice']['Value'])){
						$restrictionNotice = '';
						foreach ($result['RestrictionNotice'] as $arr){
							if (empty($restrictionNotice)){
								$restrictionNotice = $arr['Value'];
							} else {
								$restrictionNotice .= ', '.$arr['Value'];
							}
						} 
					}else {
						$restrictionNotice = $result['RestrictionNotice']['Value'];
					}
                    echo $restrictionNotice;
                    ?>

                </span>
            </li>
			<?php endif;?>
			<?php if (!empty($result['Additional'])):?>
            <li class="last">
                <span class="label">Ответственные лица Потребителя:</span>
                <div class="more-list">
                    <div class="btn small border">Посмотреть список</div>
                    <div class="more-list-popup" style="display: none;">
                        <?php
                        //var_dump($result['Additional']['ProfileAdditional']);
                        //die(2);
						if(is_array($result['Additional'][0]['ProfileAdditional'])){
							foreach ($result['Additional'] as $pa) {
								if ($pa['ProfileAdditional']['Contacts']['Value'] && $pa['ProfileAdditional']['Contacts']){
									$outputContacts = '  /  '.$pa['ProfileAdditional']['Contacts']['Value'];
								} elseif($pa['ProfileAdditional']['Contacts']) {
									$outputContacts = '';
									foreach ($pa['ProfileAdditional']['Contacts'] as $c => $v) {
										$outputContacts .= '  /  ' . $v['Value'];
									}
								}
								$sName = (!empty($pa['ProfileAdditional']['Surname']))?$pa['ProfileAdditional']['Surname']:'';
								$name = (!empty($pa['ProfileAdditional']['Name']))?mb_substr($pa['ProfileAdditional']['Name'], 0, 1, 'UTF-8').'.':'';
								$mName = (!empty($pa['ProfileAdditional']['MiddleName']))?mb_substr($pa['ProfileAdditional']['MiddleName'], 0, 1, 'UTF-8') . '.':'';
								echo '<p>' . $sName .' ' . $name . $mName . ' ' . $outputContacts . '</p>';
							}
						}else{
							if ($result['Additional']['ProfileAdditional']['Contacts']['Value'] && $result['Additional']['ProfileAdditional']['Contacts']){
									$outputContacts = '  /  '.$result['Additional']['ProfileAdditional']['Contacts']['Value'];
								} elseif($result['Additional']['ProfileAdditional']['Contacts']) {
									$outputContacts = '';
									foreach ($result['Additional']['ProfileAdditional']['Contacts'] as $c => $v) {
										$outputContacts .= '  /  ' . $v['Value'];
									}
								}
								$sName = (!empty($result['Additional']['ProfileAdditional']['Surname']))?$result['Additional']['ProfileAdditional']['Surname']:'';
								$name = (!empty($result['Additional']['ProfileAdditional']['Name']))?mb_substr($result['Additional']['ProfileAdditional']['Name'], 0, 1, 'UTF-8').'.':'';
								$mName = (!empty($result['Additional']['ProfileAdditional']['MiddleName']))?mb_substr($result['Additional']['ProfileAdditional']['MiddleName'], 0, 1, 'UTF-8') . '.':'';
								echo '<p>' . $sName .' ' . $name . $mName . ' ' . $outputContacts . '</p>';
						}
						
						
                        ?>
                    </div>
                </div>
            </li>
			<?php endif;?>
        </ul>
    </div>
    <div class="info-warning">
        <strong>Внимание!</strong>
        В случае обнаружения ошибок, а также при изменении контактной информации сообщить об этом в разделе <?=Html::a('«Написать обращения»', ['inner/fos']);?>
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
            <?php if (!empty($result['Specifications']['EDO'])):?>
                <div class="label active">Статус: <span>Активно</span></div>
                <div class="bts">
                    <?php
                    if (!empty($result['Specifications']['EmailAccount1'])) {
                        Html::a('Подробнее', ['main/edo','currentEmail' =>$result['Specifications']['EmailAccount1'], '#'=>'doc_tab_1'],['class'=>'lnk']);
                    } else {
                        Html::a('Подробнее', ['main/edo', '#'=>'doc_tab_1'],['class'=>'lnk']);
                    }

                    ?>

                </div>
            <?php else:?>
                <div class="label">Статус: <span>Не активно</span></div>
                <div class="bts">
                    <?=Html::a('Подробнее', ['main/edo', '#'=>'doc_tab_1'],['class'=>'lnk'])?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="profile-status white-box">
        <div class="white-box-title">Получение счетов по электроной почте</div>
        <div class="status-text">
            Вы можете подписаться на рассылку документов по договору энергоснабжения с ООО «РГМЭК» на адрес электронной почты
        </div>
        <div class="status-control">
            <?php if (!empty($result['Specifications']['EmailAccount1'])):?>
                <div class="label active">Статус: <span>Активно</span> (<?=$result['Specifications']['EmailAccount1']?>)</div>
                <div class="bts">
                    <?=Html::a('Подробнее', ['main/edo','currentEmail' =>$result['Specifications']['EmailAccount1'], '#'=>'doc_tab_2'],['class'=>'lnk'])?>
                </div>
            <?php else:?>
                <div class="label">Статус: <span>Не активно</span></div>
                <div class="bts">
                    <?=Html::a('Подробнее', ['main/edo', '#'=>'doc_tab_2'],['class'=>'lnk'])?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>