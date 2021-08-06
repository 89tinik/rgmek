<?php
/* @var $this yii\web\View */
/* @var $result */

/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Узнать задолжность, оплатить | ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Узнать задолжность, оплатить</strong><span class="sep"></span>
        <span>	Договор  <?= $this->context->currentContract; ?></span>
    </div>
</div>

<div class="arrear-right">
    <div class="payment-form white-box">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['ajax/create-sber-invoice'],
            'fieldConfig' => [
                'template' => "{input}{error}",
                'options' => [
                    // 'tag' => false
                ],
            ],
            'options' => [
                'class' => 'sber-form testing pay-form',
            ]
        ]); ?>

        <?= $form->field($model, 'invoice')->hiddenInput(['value' => $result['Contract']['UID'] . '|' . time()]); ?>

        <div class="title">Оплата</div>
        <?php
        $ee = str_replace(',', '.', $result['Payment']['ElectricityDebt']);
        $disabledEe = ($ee) ? ' ' : ['diasbled' => 'diasabled'];
        ?>
        <?= $form->field($model, 'ee', ['template' => '<div class="group">
                                                                            <div class="field ">
                                                                                {label}
                                                                                <div class="value ruble">
                                                                                    {input}
                                                                                    {error}
                                                                                </div>
						                                                    </div>
						                                                </div>'])->
        widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,}.9{1,}',
            'clientOptions' => [
                'placeholder' => ''
            ]
        ])->
        textInput(['class' => 'value ruble', 'value' => $ee, 'max' => $ee, $disabledPenalty])->
        label('Электроэнергия:', [
            'class' => 'label'
        ]) ?>

        <?php
        $penalty = str_replace(',', '.', $result['Payment']['CurrentPenalty']);
        $disabledPenalty = ($penalty) ? ' ' : ['diasbled' => 'diasabled'];
        ?>
        <?= $form->field($model, 'penalty', ['template' => '<div class="group">
                                                                            <div class="field ">
                                                                                {label}
                                                                                <div class="value ruble">
                                                                                    {input}
                                                                                    {error}
                                                                                </div>
						                                                    </div>
						                                                </div>'])->
        widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9{1,}.9{1,}',
            'clientOptions' => [
                'placeholder' => ''
            ]
        ])->
        textInput(['class' => 'value ruble', 'value' => $penalty, 'max' => $penalty, $disabledPenalty])->
        label('Пени:', [
            'class' => 'label'
        ]) ?>
        <div class="group-price">
            <div class="label">Итого:</div>

            <div class="price all-price"><?= (!empty($all = $ee + $penalty)) ? number_format($all, 2, ',', ' ') . ' ₽' : 0; ?></div>
        </div>
        <?= Html::submitButton('Перейти к оплате', ['class' => 'btn submit-btn']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="arrear-left">
    <div class="arrear-summary border-box">
        <div class="info-top">
            <div class="title">
                <!--Какой-то текст ни кому не известно-->
                <strong>ДОГОВОР <?= $this->context->currentContract; ?></strong>
            </div>
        </div>
        <div class="info-bottom">
            <div class="title">
                <div class="label">Долг на <?= date('d.m.Y') ?> электроэнергия</div>
                <div class="value"><?= (!empty($result['Contract']['Expand']['CurrentDebt'])) ? $result['Contract']['Expand']['CurrentDebt'] . ' руб.' : 0; ?></div>
            </div>
            <div class="list">
                <ul>
                    <li>
                        <span class="name">Электроэнергия</span>
                        <span class="value"><?= (!empty($result['Contract']['Expand']['ElectricityDebt'])) ? $result['Contract']['Expand']['ElectricityDebt'] . ' руб.' : 0; ?></span>
                    </li>
                    <?php if (!empty($result['Contract']['Expand']['CurrentPenalty'])) : ?>
                        <li>
                            <span class="name">Пени</span>
                            <span class="value"><?= $result['Contract']['Expand']['CurrentPenalty'] . ' руб.'; ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($result['Contract']['Expand']['Duty'])) : ?>
                        <li>
                            <span class="name">Госпошлина</span>
                            <span class="value"><?= $result['Contract']['Expand']['Duty'] . ' руб.'; ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="title">
                <div class="label">Предстоящие платежи</div>
                <div class="value"><?= (!empty($result['Contract']['Expand']['UpcomingDebt'])) ? $result['Contract']['Expand']['UpcomingDebt'] . ' руб.' : 0; ?></div>
            </div>
            <!--div class="list">
                <ul>
                    <li>
                        <span class="name">Пени</span>
                        <span class="value"><?= (!empty($result['Contract']['UpcomingPenalty'])) ? $result['Contract']['UpcomingPenalty'] . ' руб.' : 0; ?></span>
                    </li>
                </ul>
            </div-->
            <?php if (!empty($result['Expand']['Overpayment'])): ?>
                <div class="title">
                    <div class="label">Преплата</div>
                    <div class="value"><?= $result['Expand']['Overpayment'] . ' руб.' ?></div>
                </div>
            <?php endif; ?>
            <div class="itog">
                Итого:
                <div class="value"><?= (!empty($result['Contract']['TotalDebt'])) ? $result['Contract']['TotalDebt'] . ' ₽' : 0; ?> </div>
            </div>
        </div>
        <?php if (!empty($result['Contract']['DateDisable'])): ?>
            <div class="info-notice">Вы включены в график отключений на <?= $result['Contract']['DateDisable'] ?></div>
        <?php endif; ?>

    </div>
    <?php if (isset($result['Account'])) : ?>
        <div class="arrear-lists white-box">
            <div class="white-box-title">Счета текущего расчетного периода</div>
            <div class="list">
                <ul class="wrap-invoice">
                    <?php
                    if (isset($result['Account']['FullName'])) {
                        echo $this->render('_invoiceItem', [
                            'invoice' => $result['Account']
                        ]);
                    } else {
                        foreach ($result['Account'] as $arr) {
                            echo $this->render('_invoiceItem', [
                                'invoice' => $arr
                            ]);
                        }
                    }
                    ?>


                </ul>
            </div>
        </div>
    <?php endif; ?>
    <div class="bts">
        <!--        <a href="#" class="btn border full aj-all-invoice" data-uid="-->
        <? //= $result['Contract']['UID'] ?><!--">Все выставленные счета</a>-->
        <p>Все выставленные счета находятся в
            разделе <?= Html::a('"Счета"', ['main/invoice', 'uid' => $result['Contract']['UID']], ['class' => 'ploader']) ?></p>

    </div>
</div>

