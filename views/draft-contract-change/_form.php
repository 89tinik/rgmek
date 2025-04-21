<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DraftContractChangeForm */
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
                'options' => $options
            ]);
            ?>



            <?= $form->field($model, 'director_position')->textInput(['class' => 'form-control a-send']) ?>

            <?= $form->field($model, 'director_full_name', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">ФИО руководителя или уполномоченного сотрудника, в лице которого будет заключено соглашение.</div>'
            ])->textInput([
                'class' => 'form-control a-send'
            ]) ?>

            <?= $form->field($model, 'director_order')->textInput(['class' => 'form-control a-send']) ?>





            <div class="group two-col dates">
                <?= $form->field(
                    $model,
                    'contract_price'
                )->textInput([
                    'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_price), 2, '.', ' '),
                    'class' => 'form-control a-send num-format',
                    'disabled' => 'disabled'
                ]) ?>
                <?= $form->field(
                    $model,
                    'contract_volume'
                )->textInput([
                    'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_volume), 2, '.', ' '),
                    'class' => 'form-control a-send num-format',
                    'disabled' => 'disabled'
                ]) ?>
            </div>

            <div class="group two-col dates">
                <?= $form->field(
                    $model,
                    'contract_price_new'
                )->textInput([
                    'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_price_new), 2, '.', ' '),
                    'class' => 'form-control a-send num-format calc-new-price'
                ]) ?>

                <?= $form->field($model, 'contract_volume_new', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Объем рассчитан исходя из введенной Вами цены контракта и цены за 1 кВтч.
                    Отметьте «Включать планируемый объем», если хотите, чтобы этот параметр был указан в соглашении</div>'
                ])->textInput([
                    'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_volume_new), 2, '.', ' '),
                    'class' => 'form-control a-send num-format calc-new-volume',
                    'readonly' => true,
                    'data-tarif' => $contractsInfo['PricePerPiece']
                ]) ?>
            </div>




            <?= $form->field($model, 'contract_volume_plane_include')->checkbox([
                'class' => 'a-send styler',
            ]) ?>

        </div>
<p>Направьте соглашение в ООО «Р-Энергия» для подписания в системе электронного документооборота</p>
        <?= Html::button('Сформировать соглашение', ['class' => 'btn btn-success submit-btn bottom-button', 'type' => 'submit']) ?>

        <?php ActiveForm::end(); ?>
        <?php
        if (Yii::$app->session->has('response1C')) {
            echo Html::button('Показать ответ 1С', ['class' => 'btn bottom-button show-response']);
            ?>
            <div class="response-1c" style="display: none"><textarea><?=Yii::$app->session->get('response1C')?></textarea></div>
            <?php
            Yii::$app->session->remove('response1C');
        }
        ?>
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
