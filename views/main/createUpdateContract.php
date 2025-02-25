<?php

use app\models\DraftContract;
use yii\helpers\Html;

/* @var $draftContract app\models\DraftContract */
/* @var $draftContractChange app\models\DraftContractChange */
/* @var $draftTermination app\models\DraftTermination */

?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Заключение/изменение договора</strong>
    </div>
</div>
<div class="main-index">

    <div class="buttons">
        <?= Html::a(
            'Направить заявление на заключение контракта (договора) энергоснабжения на следующий период'.
            ($draftContract ? '(черновик)' : ''),
            $draftContract ? ['draft-contract/update', 'id' => $draftContract->id] : ['draft-contract/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>
        <?= Html::a(
            'Сформировать соглашение об изменении цены действующего контракта (договора)'.
            ($draftContractChange ? '(черновик)' : ''),
            $draftContractChange ? ['draft-contract-change/update', 'id' => $draftContractChange->id] : ['draft-contract-change/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>
        <?= Html::a(
            ' Сформировать соглашение о расторжении действующего контракта (договора)'.
            ($draftTermination ? '(черновик)' : ''),
            $draftTermination ? ['draft-termination/update', 'id' => $draftTermination->id] : ['draft-termination/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>


    </div>

</div>
