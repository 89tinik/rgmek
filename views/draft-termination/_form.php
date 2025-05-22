<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DraftTerminationForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $userModel yii\web\User */
/* @var $contractsInfo array */
/* @var $userDrafts array */
?>

<div class="draft-contract-form">


    <?php
    if (Yii::$app->session->hasFlash('success')) {
        echo '<div class="form-message"><div class="success">' . Yii::$app->session->getFlash('success') . '</div></div>';
    }
    if (Yii::$app->session->hasFlash('error')) {
        echo '<div class="form-message"><div class="error">' . Yii::$app->session->getFlash('error') . '<br><br><span>После заполнения необходимо повторно отправить заявку</span></div></div>';
    }
    ?>

    <?php $form = ActiveForm::begin([
        'validateOnBlur' => false,
        'validateOnChange' => true,
        'validateOnSubmit' => false,
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'ajax-c-form', 'onkeydown'=> 'return event.key !== "Enter";']]); ?>
    <?= $form->field($model, 'user_id')->hiddenInput([
        'value' => $userModel->id,
        'class' => 'field-user-id'
    ])->label(false); ?>

    <div class="form-tab active">
        <?php
        $contractData = getSelectData($contractsInfo['ContractNumberList']['item']);
        $options = [];
        foreach ($contractData[1] as $key => $dbid) {
            $options[$key] = [
                'data-dbid' => $dbid,
                'data-url' => array_key_exists($key, $userDrafts) ? Url::to(['update', 'id' => $userDrafts[$key]]) : Url::to(['create', 'contract' => $key]),
            ];
        }
        echo $form->field($model, 'contract_id', [
            'inputOptions' => [
                'class' => 'styler select__default send-contract',
            ],
        ])->dropDownList($contractData[0], [
            'value' => $model->contract_id ?? array_search($contractsInfo['ContractNumber'], $contractData[1]),
            'options' => $options
        ]);
        ?>



        <?= $form->field($model, 'director_position')->textInput([
            'class' => 'form-control a-send']) ?>

        <?= $form->field($model, 'director_full_name', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">ФИО руководителя или уполномоченного сотрудника, в лице которого будет заключен договор.</div>'
        ])->textInput([
            'class' => 'form-control a-send']) ?>

        <?= $form->field($model, 'director_order')->textInput([
            'class' => 'form-control a-send']) ?>

        <?= $form->field(
            $model,
            'contract_price'
        )->textInput([
            'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_price), 2, '.', ' '),
            'readonly' => true,
            'class' => 'form-control a-send num-format'
        ]) ?>


        <?= $form->field($model, 'contract_volume_price', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Стоимость потребленной электроэнергии по договору</div>'
        ])->textInput([
            'class' => 'form-control a-send num-format',
            'readonly' => true,
            'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_volume_price), 2, '.', ' ')
        ]) ?>

    </div>
<p>Уважаемый клиент! Расторжение контракта (договора) возможно при условии полной оплаты стоимости потребленной электроэнергии. Направьте соглашение в ООО «Р-Энергия» для подписания в системе электронного документооборота</p>
    <?= Html::button('Сформировать соглашение', ['class' => 'btn btn-success submit-btn bottom-button', 'type' => 'submit']) ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
function getSelectData($data)
{
    $dataArr = [];
    $idDBArr = [];
    if (array_key_exists('id', $data)) {
        $dataArr[$data['description']] = $data['description'];
        $idDBArr[$data['description']] = $data['id'];
    } else {
        foreach ($data as $dataVal) {
            $dataArr[$dataVal['description']] = $dataVal['description'];
            $idDBArr[$dataVal['description']] = $dataVal['id'];
        }
    }
    return [$dataArr, $idDBArr];
}

?>
