<?php

/* @var $this yii\web\View */

/* @var $notifications */


use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Информация об электропотреблении |  ЛК Р-Энергия';
var_dump($notifications);
$sectionNamesArray = [
        'agreement_drafts' => 'Проект договора / соглашения',
        'restriction_notifications' => 'Уведомления об ограничении....',
        'device_maintenance_notifications' => 'Уведомления о предстоящей....',
        'admission_certificates' => 'Акт допуска....'
];
?>
<?php foreach ($notifications['sections'] as $section) : ?>
    <div class="objects-item wrap-object history-wrap ">
        <div class="objects-head" style="margin:0;">
            <div class="name"><a href="#"><?= $sectionNamesArray[$section['name']] ?></a></div>
        </div>


        <div class="objects-body" style="display: none;">
            <div class="invoice-table history">
                <table class="tab-invoice">
                    <tbody>
                    <tr>
                        <td>Наименование</td>
                        <!--                        <td>Дата</td>-->
                        <!--                        <td>Номер</td>-->
                        <td></td>
                    </tr>
                    <?php if (isset($section['documents']['name'])) : ?>
                        <tr class="line">
                            <td>
                                <div class="price"><?= $section['documents']['fullName'] ?></div>
                            </td>
                            <!--                            <td>-->
                            <!--                                <div class="checkbox-item">-->
                            <!--                                    <strong>-->
                            <?php //=$document['date']?><!--</strong>-->
                            <!--                                </div>-->
                            <!--                            </td>-->
                            <!--                            <td>-->
                            <!--                                <div class="price">-->
                            <?php //=$document['number']?><!--</div>-->
                            <!--                            </td>-->
                            <td>
                                <?= Html::a('Открыть', ['main/access-notification-file', 'uidfile' => $section['documents']['uid']], ['class' => 'btn small right print', 'target' => '_blank']) ?>

                            </td>

                        </tr>
                    <?php else: ?>
                        <?php foreach ($section['documents'] as $document) : ?>
                            <tr class="line">
                                <td>
                                    <div class="price"><?= $document['fullName'] ?></div>
                                </td>
                                <!--                            <td>-->
                                <!--                                <div class="checkbox-item">-->
                                <!--                                    <strong>-->
                                <?php //=$document['date']?><!--</strong>-->
                                <!--                                </div>-->
                                <!--                            </td>-->
                                <!--                            <td>-->
                                <!--                                <div class="price">-->
                                <?php //=$document['number']?><!--</div>-->
                                <!--                            </td>-->
                                <td>
                                    <?= Html::a('Открыть', ['main/access-notification-file', 'uidfile' => $document['uid']], ['class' => 'btn small right print', 'target' => '_blank']) ?>

                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="objects-more">
            <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
                <span>Развернуть</span>
            </a>
        </div>
    </div>
<?php endforeach; ?>