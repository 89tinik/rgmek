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
        <div class="form-message">
            <?php
            if (Yii::$app->session->hasFlash('success')) {
                echo '<div class="success">' . Yii::$app->session->getFlash('success') . '</div>';
            }
            if (Yii::$app->session->hasFlash('error')) {
                echo '<div class="error">' . Yii::$app->session->getFlash('error') . '</div>';
            }
            ?>
        </div>
        <?php $form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => true,
            'validateOnSubmit' => false,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'ajax-c-form']]); ?>
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



            <?= $form->field($model, 'directorPosition')->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['DirectorPosition']) ? '' : $contractsInfo['DirectorPosition'],
                'disabled' => true]) ?>

            <?= $form->field($model, 'directorFullName', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">ФИО руководителя или уполномоченного сотрудника, в лице которого будет заключен договор.
Если ФИО не совпадает с указанным, пожалуйста, прикрепите ниже приказ о назначении.</div>'
            ])->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['DirectorFullName']) ? '' : $contractsInfo['DirectorFullName'],
                'disabled' => true
            ]) ?>

            <?= $form->field($model, 'directorOrder')->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['DirectorOrder']) ? '' : $contractsInfo['DirectorOrder'],
                'disabled' => true]) ?>
            <div id="wrap-uploaded-files">
                <?php
                if (!empty($model->files)) {
                    echo $this->render('_uploaded-files', ['files' => $model->files, 'draft' => $model->id]);
                }
                ?>
            </div>
            <?= $form->field($model, 'filesUpload[]')->fileInput([
                'multiple' => true,
                'class' => 'input-file draft-files'
            ]); ?>



            <div class="group two-col dates">
                <?= $form->field(
                    $model,
                    'contract_price'
                )->textInput([
                    'value' => number_format($model->contract_price, 2, '.', ' ')
                        ?? number_format($contractsInfo['ContractPrice'], 2, '.', ' '),
                    'class' => 'form-control a-send num-format'
                ]) ?>
                <?= $form->field(
                    $model,
                    'contract_volume'
                )->textInput([
                    'value' => number_format($model->contract_volume, 2, '.', ' ')
                        ?? number_format($contractsInfo['ContractVolume'], 2, '.', ' '),
                    'class' => 'form-control a-send num-format'
                ]) ?>
            </div>

            <div class="group two-col dates">
                <?= $form->field(
                    $model,
                    'contract_price_new'
                )->textInput([
                    'value' => number_format($model->contract_price_new, 2, '.', ' ')
                        ?? number_format($contractsInfo['ContractPriceNew'], 2, '.', ' '),
                    'class' => 'form-control a-send num-format'
                ]) ?>

                <?= $form->field($model, 'contract_volume_new', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Объем рассчитан исходя из введенной Вами цены контракта и цены за 1 кВтч.
                    Отметьте «Включать планируемый объем», если хотите, чтобы этот параметр был указан в соглашении</div>'
                ])->textInput([
                    'value' => number_format($model->contract_volume_new, 2, '.', ' ')
                        ?? number_format($contractsInfo['ContractVolumeNew'], 2, '.', ' '),
                    'class' => 'form-control a-send num-format'
                ]) ?>
            </div>




            <?= $form->field($model, 'contract_volume_plane_include')->checkbox([
                'class' => 'a-send styler',
            ]) ?>

            <?= $form->field($model, 'contact_name')->textInput([
                'class' => 'form-control a-send required min-length',
                'min' => '3',
                'maxlength' => true
            ])->label('Контактное лицо по заявлению*') ?>

            <?= $form->field($model, 'contact_phone')->textInput([
                'class' => 'form-control a-send required min-length',
                'maxlength' => true,
                'min' => '6',
                'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20);",
            ])->label('Телефон*')   ?>

            <?= $form->field($model, 'contact_email')->textInput([
                'class' => 'form-control a-send required email',
                'maxlength' => true
            ])->label('E-mail*') ?>
        </div>


        <?= Html::a('Отправить заявление', ['draft-contract-change/send-draft', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success submit-btn bottom-button']) ?>

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
