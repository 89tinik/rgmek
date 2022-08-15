<?php

/* @var $this yii\web\View */
/* @var $result */
/* @var $currentTU */

/* @var $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'История показаний |  ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>История показаний</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
    </div>
</div>

<div class="payment-filter white-box">
    <?php
    if (Yii::$app->session->hasFlash('success')) {
        echo Yii::$app->session->getFlash('success');
    }
    if (Yii::$app->session->hasFlash('error')) {
        echo Yii::$app->session->getFlash('error');
    }
    ?>
    <?php $form = ActiveForm::begin([
        'action' => [''],
        'method' => 'get',
        'options' => [
//            'method' => 'get'
        ],
        'fieldConfig' => [
            'template' => '<div class="field">
                                <div class="value">
                                    <span>{label}</span>
                                     {input}
                                     {error}
                                </div>
                            </div>',
            'options' => [
                'tag' => false
            ]
        ]
    ]);

    ?>



    <?= $form->field($model, 'uidtu', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'uidobject', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'uid', ['template' => '{input}'])->hiddenInput(); ?>

    <div class="group large">
        <div class="label">Выбрать период:</div>
        <?= $form->field($model, 'withdate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'from_dialog']) ?>
        <?= $form->field($model, 'bydate')->textInput(['autocomplete' => 'off', 'readonly' => 'readonly', 'id' => 'to_dialog']) ?>

        <?= Html::submitButton('Сформировать', ['class' => 'btn submit-btn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<h1 class="object-name-history"><?= $result['FullName'] ?></h1>
<?php
if (!empty($result['CalculationAlgorithm'])) {
    echo '<h3 class="colculation-history">' . $result['CalculationAlgorithm'] . '</h3>';
}
?>

<?php
if (isset($result['PU'])) {
    if (isset($result['PU']['Name'])) {
        echo $this->render('_historyIndicationItem', [
            'pu' => $result['PU'],
            'currentTU' => $currentTU,
            'model' => $model
        ]);
    } else {
        foreach ($result['PU'] as $arr) {
            echo $this->render('_historyIndicationItem', [
                'pu' => $arr,
                'currentTU' => $currentTU,
                'model' => $model
            ]);
        }
    }?>
    <div class="bts">
        <?= Html::a('Печать', [
            'main/access-history-file',
            'print' => 'true',
            'action' => 'download_history_ind',
            'uidtu' => $model->uidtu,
            'uidobject' => $model->uidobject,
            'withdate' => $model->withdate,
            'bydate' => $model->bydate
        ], ['class' => 'btn small right print', 'target' => '_blank']) ?>
        <?= Html::a('Скачать', [
            'main/access-history-file',
            'print' => 'false',
            'action' => 'download_history_ind',
            'uidtu' => $model->uidtu,
            'uidobject' => $model->uidobject,
            'withdate' => $model->withdate,
            'bydate' => $model->bydate
        ], ['class' => 'btn small right download', 'target' => '_blank']) ?>
    </div>
<?php } ?>
