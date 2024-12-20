<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DraftContractChangeForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $userModel yii\web\User */
/* @var $contractsInfo array */
?>

    <div class="draft-contract-form">

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
            echo $form->field($model, 'contract_id', [
                'inputOptions' => [
                    'class' => 'styler select__default send-contract',
                ],
            ])->dropDownList($contractData[0], [
                'prompt' => '',
                'options' => array_map(function ($v) {
                    return ['data-dbid' => $v];
                }, $contractData[1])
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
            <ul id="filesList"></ul>
            <?= Html::button('Загрузить в черновик', [
                'class' => 'btn btn-success submit-file-btn-js',
                'style' => 'display:none'
            ]) ?>


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
                <?= $form->field(
                    $model,
                    'contract_volume_new'
                )->textInput([
                    'value' => number_format($model->contract_volume_new, 2, '.', ' ')
                        ?? number_format($contractsInfo['ContractVolumeNew'], 2, '.', ' '),
                    'class' => 'form-control a-send num-format'
                ]) ?>
                <a class="btn small border input-tooltip input-tooltip-js">?</a>
                <div style="display: none">Объем рассчитан исходя из введенной Вами цены контракта и цены за 1 кВтч.
                    Отметьте «Включать планируемый объем», если хотите, чтобы этот параметр был указан в соглашении
                </div>
            </div>




            <?= $form->field($model, 'contract_volume_plane_include')->checkbox([
                'class' => 'a-send styler',
            ]) ?>

            <?= $form->field($model, 'contact_name')->textInput([
                'class' => 'form-control a-send',
                'maxlength' => true
            ]) ?>

            <?= $form->field($model, 'contact_phone')->textInput([
                'class' => 'form-control a-send',
                'maxlength' => true
            ]) ?>

            <?= $form->field($model, 'contact_email')->textInput([
                'class' => 'form-control a-send',
                'maxlength' => true
            ]) ?>
        </div>

        <div class="form-tab">
            <h2>Заявление на заключение контракта (договора) энергоснабжения №<span
                        class="contract-number"><?= $model->contract_id ?></span> сформирована</h2>
            <p>Проверьте заявление. При необходимости вернитесь и измените данные.</p>
            <p><a href="#" class="btn generate-draft-pdf">PDF</a></p>
            <p>Сформированный черновик заявления будет храниться в Личном кабинете в течение 30 дней и доступна для
                изменения.</p>
            <p> После того, как Вы нажмете «Отправить заявление», оно поступит в ООО «РГМЭК», будет зарегистрировано и
                принято в работу.</p>
            <p> Вы сможете узнать о статусе рассмотрения заявления в разделе «Диалоги».</p>
            <p> В срок не более 10 рабочих дней Вы получите проект договора (контракта) энергоснабжения в системе
                электронного документооборота.</p>
        </div>

        <?= Html::button('Назад', ['class' => 'btn btn-success prev-btn bottom-button']) ?>
        <?= Html::button('Далее', ['class' => 'btn btn-success next-btn bottom-button']) ?>
        <?= Html::a('Отправить заявление', ['draft-contract-change/send-draft', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success submit-btn hidden bottom-button']) ?>

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