<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DraftContractForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $userModel yii\web\User */
/* @var $contractsInfo array */
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
            //var_dump($contractsInfo['ContractNumberList']['item']);
            // die('12');
            $contractData = getSelectData($contractsInfo['ContractNumberList']['item']);
            echo $form->field($model, 'contract_id', [
                'inputOptions' => [
                    'class' => 'styler select__default send-contract',
                ],
            ])->dropDownList($contractData[0], [
                'options' => array_map(function ($v) {
                    return ['data-dbid' => $v];
                }, $contractData[1])
            ]);
            ?>

            <?php
            $contractTypeData = getSelectData($contractsInfo['ContractTypeList']['item']);
            echo $form->field($model, 'contract_type', [
                'inputOptions' => [
                    'class' => 'styler select__default',
                ],
            ])->dropDownList($contractTypeData[0], [
                'prompt' => '',
                'value' => $model->contract_type ?? array_search($contractsInfo['ContractType'], $contractTypeData[1]),
                'options' => array_map(function ($v) {
                    return ['data-dbid' => $v];
                }, $contractTypeData[1])
            ]);
            ?>

            <div class="group two-col dates">
                <div class="label">Период действия контракта(договора):</div>
                <?= $form->field(
                    $model,
                    'from_date'
                )->textInput([
                    'value' => $model->from_date ?? $contractsInfo['WithDate'],
                    'class' => 'form-control a-send from-date'
                ]) ?>
                <?= $form->field(
                    $model,
                    'to_date'
                )->textInput([
                    'value' => $model->to_date ?? $contractsInfo['ByDate'],
                    'class' => 'form-control a-send to-date'
                ]) ?>
                <a class="btn small border input-tooltip input-tooltip-js">?</a>
                <div style="display: none">Задайте нужный период</div>
            </div>

            <?php
            $basisPurchaseData = getSelectData($contractsInfo['BasisList']['item']);
            echo $form->field($model, 'basis_purchase', [
                'inputOptions' => [
                    'class' => 'styler select__default',
                ],
                'template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Основание закупки будет указано в преамбуле договора. Выберите нужное или заполните, или
 оставьте строку не заполненной.</div>'
            ])->dropDownList($basisPurchaseData[0], [
                'prompt' => '',
                'value' => $model->basis_purchase ?? array_search($contractsInfo['Basis'], $basisPurchaseData[1]),
                'options' => array_map(function ($v) {
                    return ['data-dbid' => $v];
                }, $basisPurchaseData[1])
            ]);

            ?>

            <?= $form->field($model, 'ikz', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">ИЗК будет указан в предмете договора</div>'
            ])->textInput(['class' => 'form-control a-send', 'maxlength' => true]) ?>


            <div class="group three-col">
                <?= $form->field(
                    $model,
                    'contractPriceForecast'
                )->textInput([
                    'class' => 'form-control a-send num-format',
                    'value' => number_format($contractsInfo['ContractPriceForecast'], 2, '.', ' '),
                    'disabled' => true
                ]) ?>
                <?= $form->field(
                    $model,
                    'contractVolumeForecast'
                )->textInput([
                    'class' => 'form-control a-send num-format',
                    'value' => number_format($contractsInfo['ContractVolumeForecast'], 2, '.', ' '),
                    'disabled' => true
                ]) ?>
                <?= $form->field(
                    $model,
                    'pricePerPiece'
                )->textInput([
                    'class' => 'form-control a-send num-format',
                    'value' => number_format($contractsInfo['PricePerPiece'], 2, '.', ' '),
                    'disabled' => true
                ]) ?>
                <a class="btn small border input-tooltip input-tooltip-js">?</a>
                <div style="display: none">Данные рассчитаны за выбранный Вами период действия договора, исходя из
                    статистики за предыдущий период (фактического потребления и среднемесячной нерегулируемой цены) с
                    учетом
                    прогнозного изменения цены
                </div>
            </div>

            <div class="group two-equal-col">
                <?= $form->field($model, 'contract_price', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Цена договора будет указана в разделе 4 договора «порядок определения стоимости поставленной
 электроэнергии (мощности) и порядок оплаты»</div>'
                ])->textInput([
                    'class' => 'form-control a-send calc-price calc-price-all num-format',
                    'value' => number_format($model->contract_price, 2, '.', ' ') ?? number_format($contractsInfo['ContractPrice'], 2, '.', ' ')
                ]) ?>

                <?= $form->field($model, 'contract_volume_plane', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Объем рассчитан исходя из введенной Вами цены контракта и цены за 1 кВтч. Отметьте «Включать
 планируемый объем», если хотите, чтобы этот параметр был указан в разделе 4 договора</div>'
                ])->textInput([
                        'class' => 'form-control a-send num-format',
                        'value' => number_format($model->contract_volume_plane, 2, '.', ' ')
                ]) ?>
            </div>

            <?= $form->field($model, 'contract_volume_plane_include')->checkbox([
                'class' => 'a-send styler',
            ]) ?>

            <?php
            $fundingSourceData = getSelectData($contractsInfo['FundingSourceList']['item']);
            echo $form->field($model, 'source_funding', [
                'inputOptions' => [
                    'class' => 'styler select__default',
                ],
                'template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Источник финансирования будет отражен в разделе 4 договора</div>'
            ])->dropDownList($fundingSourceData[0], [
                'prompt' => '',
                'value' => $model->source_funding ?? array_search($contractsInfo['FundingSource'], $fundingSourceData[1]),
                'options' => array_map(function ($v) {
                    return ['data-dbid' => $v];
                }, $fundingSourceData[1])
            ]);

            ?>

            <?= $form->field($model, 'off_budget')->checkbox([
                'class' => 'a-send styler off-budget-input',
            ]) ?>
            <div class="off-budget-section" style="display: <?= ($model->off_budget) ? 'block' : 'none' ?>">
                <?= $form->field($model, 'off_budget_name', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Информация будет отражена в  разделе 4.1 договора</div>'
                ])->textInput(['class' => 'form-control a-send', 'maxlength' => true]) ?>

                <?= $form->field($model, 'off_budget_value', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Информация будет отражена в  разделе 4.1 договора</div>'
                ])->textInput([
                    'class' => 'form-control a-send calc-price calc-price-off num-format',
                    'maxlength' => true,
                    'value' => number_format($model->off_budget_value, 2, '.', ' ')
                ]) ?>

                <?= $form->field($model, 'budget_value', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js num-format">?</a>
<div style="display: none">Рассчитывается, как разность цены договора и суммы средств из иного источника</div>'
                ])->textInput([
                    'class' => 'form-control a-send calc-result',
                    'maxlength' => true,
                    'readonly' => 'readonly',
                    'value' => number_format($model->budget_value, 2, '.', ' ')
                ]) ?>

            </div>
        </div>
        <div class="form-tab">
            <h2>Контакты ответственных лиц потребителя будут указаны в разделе 10 договора «Реквизиты сторон».
                Заполнение данных является обязательным.</h2>
            <div class="group two-col dates">
                <div class="label">Контакты для получения уведомлений о введении ограничения</div>
                <?= $form->field(
                    $model,
                    'user_phone'
                )->textInput(['class' => 'form-control a-send', 'placeholder' => 'Телефон'])->label(false) ?>
                <?= $form->field(
                    $model,
                    'user_email'
                )->textInput(['class' => 'form-control a-send', 'placeholder' => 'E-mail'])->label(false) ?>
                <a class="btn small border input-tooltip input-tooltip-js">?</a>
                <div style="display: none">Сведения, которые указаны в действующем договоре (контракте).
                    Вы можете изменить их.
                </div>
            </div>

            <?= $form->field($model, 'responsible4DeviceContactFN', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Лицо, ответственное за обеспечение сохранности прибора учета и систем учета, предоставление доступа к
 месту установки прибора учета, снятие и 
предоставление показаний прибора учета, в том числе акта снятия показаний прибора учета</div>'
            ])->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['Responsible4DeviceContact']['FullName']) ? '' : $contractsInfo['Responsible4DeviceContact']['FullName'],
                'disabled' => true
            ]) ?>
            <div class="group two-col">
                <?= $form->field(
                    $model,
                    'responsible4DeviceContactP'
                )->textInput([
                    'class' => 'form-control a-send',
                    'value' => empty($contractsInfo['Responsible4DeviceContact']['Phone']) ? '' : $contractsInfo['Responsible4DeviceContact']['Phone'],
                    'disabled' => true
                ])->label(false) ?>
                <?= $form->field(
                    $model,
                    'responsible4DeviceContactE'
                )->textInput([
                    'class' => 'form-control a-send',
                    'value' => empty($contractsInfo['Responsible4DeviceContact']['Email']) ? '' : $contractsInfo['Responsible4DeviceContact']['Email'],
                    'disabled' => true
                ])->label(false) ?>
            </div>


            <?= $form->field($model, 'responsible4CalculationContactFN', [
                'template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Лицо, ответственное за взаиморасчеты и получение счетов на оплату</div>'
            ])->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['Responsible4CalculationContact']['FullName']) ? '' : $contractsInfo['Responsible4CalculationContact']['FullName'],
                'disabled' => true]) ?>
            <div class="group two-col">
                <?= $form->field(
                    $model,
                    'responsible4CalculationContactP'
                )->textInput([
                    'class' => 'form-control a-send',
                    'value' => empty($contractsInfo['Responsible4CalculationContact']['Phone']) ? '' : $contractsInfo['Responsible4CalculationContact']['Phone'],
                    'disabled' => true
                ])->label(false) ?>
                <?= $form->field(
                    $model,
                    'responsible4CalculationContactE'
                )->textInput([
                    'class' => 'form-control a-send',
                    'value' => empty($contractsInfo['Responsible4CalculationContact']['Email']) ? '' : $contractsInfo['Responsible4CalculationContact']['Email'],
                    'disabled' => true])->label(false) ?>
            </div>


            <?= $form->field($model, 'directorFullName', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">ФИО руководителя или уполномоченного сотрудника, в лице которого будет заключен договор.
Если ФИО не совпадает с указанным, пожалуйста, прикрепите ниже приказ о назначении.</div>'
            ])->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['DirectorFullName']) ? '' : $contractsInfo['DirectorFullName'],
                'disabled' => true
            ]) ?>

            <?= $form->field($model, 'directorPosition')->textInput([
                'class' => 'form-control a-send',
                'value' => empty($contractsInfo['DirectorPosition']) ? '' : $contractsInfo['DirectorPosition'],
                'disabled' => true]) ?>

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
            <p><?=Html::a('PDF', ['draft-contract/generate-pdf'], ['class'=>'btn generate-draft-pdf'])?></p>
            <p>Сформированный черновик заявления будет храниться в Личном кабинете в течение 30 дней и доступна для
                изменения.</p>
            <p> После того, как Вы нажмете «Отправить заявление», оно поступит в ООО «РГМЭК», будет зарегистрировано и
                принято в работу.</p>
            <p> Вы сможете узнать о статусе рассмотрения заявления в разделе «Диалоги».</p>
            <p> В срок не более 10 рабочих дней Вы получите проект договора (контракта) энергоснабжения в системе
                электронного документооборота.</p>
        </div>

        <?= Html::button('Назад', ['class' => 'btn btn-success prev-btn bottom-button hidden']) ?>
        <?= Html::button('Далее', ['class' => 'btn btn-success next-btn bottom-button']) ?>
        <?= Html::a('Отправить заявление', ['draft-contract/send-draft', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success submit-btn hidden bottom-button']) ?>

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
