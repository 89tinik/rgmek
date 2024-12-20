<?php

use app\models\DraftContract;
use yii\helpers\Html;

/* @var $draftContract app\models\DraftContract */
/* @var $draftDelContract app\models\DraftDelContract */
/* @var $draftContractChange app\models\DraftContractChange */

?>

<div class="main-index">

    <h1>Выберите действие</h1>

    <div class="buttons">
        <?= Html::a(
            DraftContract::TITLE.
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
            ($draftContract2 ? '(черновик)' : ''),
            $draftContract3 ? ['draft-contract/update', 'id' => $draftContract->id] : ['draft-contract/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>


    </div>

</div>
