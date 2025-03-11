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
<div style="display: none">ФИО руководителя или уполномоченного сотрудника, в лице которого будет заключен договор.
Если ФИО не совпадает с указанным, пожалуйста, прикрепите ниже приказ о назначении.</div>'
        ])->textInput([
            'class' => 'form-control a-send']) ?>

        <?= $form->field($model, 'director_order')->textInput([
            'class' => 'form-control a-send']) ?>
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




        <?= $form->field(
            $model,
            'contract_price'
        )->textInput([
            'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_price), 2, '.', ' '),
            'class' => 'form-control a-send num-format'
        ]) ?>


        <?= $form->field($model, 'contract_volume_price', ['template' => '{label}{input}{hint}{error}
<a class="btn small border input-tooltip input-tooltip-js">?</a>
<div style="display: none">Стоимость потребленной электроэнергии по договору</div>'
        ])->textInput([
            'class' => 'form-control a-send num-format',
            'value' => number_format((float)preg_replace('/[^0-9.]/', '', $model->contract_volume_price), 2, '.', ' ')
        ]) ?>



        <?= $form->field($model, 'contact_name')->textInput([
            'class' => 'form-control a-send required min-length',
            'min' => '3',
            'maxlength' => true
        ])->label('Контактное лицо по заявлению*')  ?>

        <?= $form->field($model, 'contact_phone')->textInput([
            'class' => 'form-control a-send required min-length',
            'maxlength' => true,
            'min' => '6',
            'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20);",
        ])->label('Телефон*')   ?>

        <?= $form->field($model, 'contact_email')->textInput([
            'class' => 'form-control a-send required email',
            'maxlength' => true
        ])->label('E-mail*')  ?>
    </div>

    <?= Html::button('Отправить заявление', ['class' => 'btn btn-success submit-btn bottom-button', 'type' => 'submit']) ?>

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
