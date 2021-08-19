<?php

/* @var $object */
/* @var $one */

use yii\helpers\Html;

?>

<div class="contracts-item <?=($one)?'open':''?>">
    <div class="contracts-head trigger-more">
        <!--div class="subname"><?= $object['NameContracts'] ?></div-->
        <div class="name"><?= $object['Name'] ?></div>
    </div>
    <div class="contracts-body" style="display: <?=($one)?'block':'none'?>;">
        <div class="contracts-row">
            <div class="contracts-col contracts-col-1">
                <div class="contracts-list">
                    <ul>
                        <?php if (!empty($object['Expand']['PartusPunctum'])): ?>
                            <li>
                                <span class="list-label">
                                    <span>Точка поставки</span>
                                </span>
                                <span class="list-value">
                                    <span><?= $object['Expand']['PartusPunctum'] ?></span>
                                </span>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($object['Expand']['GridOrganization'])): ?>

                            <li>
                                <span class="list-label">
                                    <span>Сетевая организация, к сетям которой присоединен объект</span>
                                </span>
                                <span class="list-value">
                                    <span><?= $object['Expand']['GridOrganization'] ?></span>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($object['Expand']['MaxPower'])): ?>
                            <li>
                                <span class="list-label">
                                    <span>Максимальная мощность, кВт</span>
                                </span>
                                <span class="list-value">
                                    <span><?= $object['Expand']['MaxPower'] ?></span>
                                </span>
                            </li>
                        <?php endif; ?>



                        <?php
                        $valTP = '';
                        $info = false;
                        if (!empty($object['Expand']['TariffGroup']['Name'])) {
                            $valTP = $object['Expand']['TariffGroup']['Value'];
                            if ($object['Expand']['TariffGroup']['Name'] == 'Прочие') {
                                $info = true;
                            } else {
                                $valTP = $object['Expand']['TariffGroup']['Value'];
                            }
                        } elseif (!empty($object['Expand']['TariffGroup'][0]['Name'])) {
                            $arrNameVal = [];
                            foreach ($object['Expand']['TariffGroup'] as $arr) {
                                if($arr['Name'] == 'Прочие'){
                                    $info = true;
                                } else {
                                    $arrNameVal[] = $arr['Value'];
                                }
                            }
                            $valTP = implode(',', $arrNameVal);
                        }
                        if (!empty($object['Expand']['PriceCategory']) && $info) {
                            if ($valTP != '') {
                                $valTP .= ' / ';
                            }
                            $valTP .= $object['Expand']['PriceCategory'];
                        }
                        ?>





                        <?php if (!empty($valTP)): ?>
                            <li>
                                <span class="list-label">
                                    <span>Тариф /ценовая категория</span>
                                </span>
                                <span class="list-value">
                                    <span><?= $valTP ?></span><br>
                                    <?php if ($info): ?>
                                        <a class="btn small border btn-contracts-ask price-category-btn">?</a>
                                       <?= Html::a('Изменить', ['inner/fos'],['class'=>'btn small border btn-contracts-edit ploader'])?>
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endif; ?>





                        <?php if (!empty($object['Expand']['VoltageLevel']['Name'])): ?>
                            <li>
                                <span class="list-label">
                                    <span>Уровень напряжения для применения тарифа</span>
                                </span>
                                <span class="list-value">
                                    <span><?= $object['Expand']['VoltageLevel']['Name'] ?></span>
                                </span>
                            </li>
                        <?php elseif (!empty($object['Expand']['VoltageLevel'][0]['Name'])): ?>
                            <li>
								<span class="list-label">
                                    <span>Уровень напряжения для применения тарифа</span>
                                </span>
                                <span class="list-value">
                                    <span>
                                        <?php
                                        $arrName = [];
                                        foreach ($object['Expand']['VoltageLevel'] as $arr) {
                                            $arrName[] = $arr['Name'];
                                        }
                                        echo implode(',', $arrName);
                                        ?>
                                    </span>
                                </span>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
            <div class="contracts-col contracts-col-2">
                <div class="contracts-devices white-box">
                    <?php if (isset($object['Expand']['PU'])) { ?>
                        <div class="title">Сведения по приборам учета:
                            <a class="btn small border pu-ask pu-ask-btn">?</a>
                        </div>
                        <div class="devices-links">

                            <?php
                            if (isset($object['Expand']['PU']['Name'])) {
                                echo $this->render('_puItem', [
                                    'pu' => $object['Expand']['PU']
                                ]);
                            } else {
                                foreach ($object['Expand']['PU'] as $arr) {
                                    echo $this->render('_puItem', [
                                        'pu' => $arr
                                    ]);
                                }
                            }
                            ?>

                        </div>
                    <?php } else { ?>
                        <div class="title">Приборы учета отсутствуют</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="contracts-more">
        <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
            <span><?=($one)?'Свернуть':'Развернуть'?></span>
        </a>
    </div>
</div>
